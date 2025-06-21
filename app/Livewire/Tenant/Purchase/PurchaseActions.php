<?php

namespace App\Livewire\Tenant\Purchase;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Purchase;
use App\Models\Tenant\PurchaseVariable;
use App\Models\Tenant\Stoke;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithFileUploads;

class PurchaseActions extends Component
{

    use LivewireOperations, WithFileUploads;
    public $id;

    public $model = Purchase::class;

    public $data = [];

    public $current,$currentIndex,$sku;

    protected $rules = [
        'supplier_id' => 'required|exists:contacts,id',
        'branch_id' => 'required|exists:branches,id',
        'ref_no' => 'required',
        'order_date' => 'required|date',
        'status' => 'required|in:requested,pending,received',
        'variables' => 'required|array',
        'variables.*.product_variable_id' => 'required|exists:product_variables,id',
        'variables.*.unit_id' => 'required|exists:units,id',
        'variables.*.qty' => 'required|numeric',
        'variables.*.purchase_price' => 'required|numeric',
        'variables.*.discount_percentage' => 'required|numeric',
        'variables.*.price' => 'required|numeric',
        'variables.*.total' => 'required|numeric',
        'variables.*.x_margin' => 'required|numeric',
        'variables.*.sale_price' => 'required|numeric',
    ];

    public function mount()
    {
        $this->current = $this->model::find($this->id);

        if($this->current) {
            $this->data = [
                'supplier_id' => $this->current->supplier_id,
                'branch_id' => $this->current->branch_id,
                'ref_no' => $this->current->ref_no,
                'order_date' => $this->current->order_date,
                'status' => $this->current->status,
                'variables' => $this->current->purchaseVariables->map(function($variable){
                    return [
                        'id' => $variable->id,
                        'product_variable_id' => $variable->product_variable_id,
                        'product_variable_name' => $variable->productVariable?->name ?? "No Name",
                        'product_variable_qty' => $variable->productVariable?->stoke_total_qty ?? 0,
                        'unit_id' => $variable->unit_id,
                        'last_child_unit_name' => $variable->productVariable?->lastUnitChild()?->name ?? "No Unit",
                        'qty' => $variable->qty,
                        'returned' => $variable->returned,
                        'purchase_price' => $variable->purchase_price,
                        'discount_percentage' => $variable->discount_percentage,
                        'price' => $variable->price,
                        'total' => $variable->total,
                        'x_margin' => $variable->x_margin,
                        'sale_price' => $variable->sale_price,
                        'product_unit_id' => $variable->productVariable?->product?->unit_id ?? NULL,
                        'product_units'=> $variable->productVariable?->product?->units() ?? []
                    ];
                })->toArray()
            ];
        }
    }

    function searchSku($value) {
        if(!$value) return;
        $variable = ProductVariable::where('sku',$value)->first();
        if($variable){
            $this->addNewVariable($variable);
            $this->sku = '';
            return;
        }

        $this->swal('Error','SKU not found','error');
    }

    function addNewVariable($variable) {
        $product = $variable->product;
        $this->data['variables'][] = [
            'product_variable_id' => $variable->id,
            'product_variable_name' => $variable->name,
            'product_variable_qty' => $variable->stoke_total_qty,
            'unit_id' => $product->unit_id,
            'last_child_unit_name' => $variable->lastUnitChild()?->name ?? "No Unit",
            'qty' => 1,
            'returned' => 0,
            'purchase_price' => $variable->{'purchase_price_'.$product->price_type},
            'discount_percentage' => 0,
            'price' => $variable->{'purchase_price_'.$product->price_type},
            'total' => $variable->{'purchase_price_'.$product->price_type},
            'x_margin' => $variable->x_margin,
            'sale_price' => $variable->{'purchase_price_'.$product->price_type} + $variable->{'purchase_price_'.$product->price_type} * $variable->x_margin / 100,
            'product_unit_id'=>$product->unit_id,
            'product_units'=>$product->units()
        ];
    }

    function removeVariable($index) {
        $choosed = $this->data['variables'][$index];

        if($choosed['id']??false) {
            $this->model::find($choosed['id'])->delete();
        }

        if(isset($this->data['variables'][$index])) {
            unset($this->data['variables'][$index]);
        }

        $this->dismiss();
    }

    function returnVariable($index) {
        $return = $this->data['variables'][$index];
        PurchaseVariable::find($return['id'])->update(['returned'=>1,'returned_at'=>now()]);
        Stoke::where('product_variable_id',$return['product_variable_id'])->where('unit_id',$return['unit_id'])->decrement('qty',$return['qty']);
        $this->data['variables'][$index]['returned'] = 1;
        $this->swal('Success','Product returned successfully','success');
    }

    public function save()
    {
        if(!$this->validator()) return;

        if($this->current) {
            $this->current->update($this->data);
        } else {
            $this->current = $this->model::create($this->data);

            foreach($this->data['variables'] as $variable) {
                $this->current->purchaseVariables()->create($variable);
                $stoke = Stoke::where('product_variable_id',$variable['product_variable_id'])->where('unit_id',$variable['unit_id'])->first();
                if(!$stoke){
                    $stoke = new Stoke();
                    $stoke->product_variable_id = $variable['product_variable_id'];
                    $stoke->unit_id = $variable['unit_id'];
                }
                $stoke->qty += $variable['qty'];
                $stoke->save();
            }
        }

        return redirect()->route('admin.purchases.index');
    }

    function saveVariable($index) {
        $selectedVariable = $this->data['variables'][$index] ?? NULL;

        $rules = [
            'product_variable_id' => 'required|exists:product_variables,id',
            'unit_id' => 'required|exists:units,id',
            'qty' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'discount_percentage' => 'required|numeric',
            'price' => 'required|numeric',
            'total' => 'required|numeric',
            'x_margin' => 'required|numeric',
            'sale_price' => 'required|numeric',
        ];

        if(!$this->validator($selectedVariable,$rules)) return;

        if($selectedVariable['id']??false) {
            $newVar = $this->current->purchaseVariables()->find($selectedVariable['id']);
            $newVar->update($selectedVariable);
        }else{
            $newVar = $this->current->purchaseVariables()->create($selectedVariable);
        }


        $this->data['variables'][$index] = $newVar->toArray();

        $this->swal('Success','Variable saved successfully','success');
    }
    public function render()
    {
        $suppliers = Contact::suppliers()->get();
        $branches = Branch::active()->get();
        return view('livewire.tenant.purchase.purchase-actions',get_defined_vars());
    }
}
