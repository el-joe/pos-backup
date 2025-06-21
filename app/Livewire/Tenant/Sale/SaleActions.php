<?php

namespace App\Livewire\Tenant\Sale;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Contact;
use App\Models\Tenant\ProductVariable;
use App\Models\Tenant\Sale;
use App\Models\Tenant\SaleVariable;
use App\Models\Tenant\Stoke;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithFileUploads;

class SaleActions extends Component
{
    use LivewireOperations, WithFileUploads;
    public $id;

    public $model = Sale::class;

    public $data = [];

    public $current,$currentIndex,$sku;

    protected $rules = [
        'customer_id' => 'required|exists:contacts,id',
        'branch_id' => 'required|exists:branches,id',
        'ref_no' => 'required',
        'order_date' => 'required|date',
        // 'status' => 'required|in:requested,pending,received',
        'variables' => 'required|array',
        'variables.*.product_variable_id' => 'required|exists:product_variables,id',
        'variables.*.unit_id' => 'required|exists:units,id',
        'variables.*.qty' => 'required|numeric',
        'variables.*.sale_price' => 'required|numeric',
        'variables.*.discount_type' => 'required|in:percentage,amount',
        'variables.*.discount' => 'required|numeric',
    ];

    public function mount()
    {
        $this->current = $this->model::find($this->id);

        if($this->current) {
            $this->data = [
                'customer_id' => $this->current->customer_id,
                'branch_id' => $this->current->branch_id,
                'ref_no' => $this->current->ref_no,
                'order_date' => $this->current->order_date,
                // 'status' => $this->current->status,
                'variables' => $this->current->saleVariables->map(function($variable){
                    return [
                        'id' => $variable->id,
                        'product_variable_id' => $variable->product_variable_id,
                        'product_variable_name' => $variable->productVariable?->name ?? "No Name",
                        'product_variable_qty' => $variable->productVariable?->stoke_total_qty ?? 0,
                        'unit_id' => $variable->unit_id,
                        'last_child_unit_name' => $variable->productVariable?->lastUnitChild()?->name ?? "No Unit",
                        'qty' => $variable->qty,
                        'refunded' => $variable->refunded,
                        'sale_price' => $variable->sale_price,
                        'discount_type' => $variable->discount_type,
                        'discount' => $variable->discount??0,
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
            'refunded' => 0,
            'sale_price' => $variable->{'sell_price_'.$product->price_type}??0,
            'discount_type' => 'amount',
            'discount' => 0,
            'product_unit_id' => $variable?->product?->unit_id ?? NULL,
            'product_units'=> $variable?->product?->units() ?? []
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

    function refundVariable($index) {
        $refund = $this->data['variables'][$index];
        SaleVariable::find($refund['id'])->update(['refunded'=>1,'refunded_at'=>now()]);
        Stoke::where('product_variable_id',$refund['product_variable_id'])->where('unit_id',$refund['unit_id'])->increment('qty',$refund['qty']);
        $this->data['variables'][$index]['refunded'] = 1;
        $this->swal('Success','Product refunded successfully','success');
    }

    public function save()
    {
        if(!$this->validator()) return;

        if($this->current) {
            $this->current->update($this->data);
        } else {
            $this->current = $this->model::create($this->data);

            foreach($this->data['variables'] as $variable) {
                $this->current->saleVariables()->create($variable);
                $stoke = Stoke::where('product_variable_id',$variable['product_variable_id'])->where('unit_id',$variable['unit_id'])->first();
                if(!$stoke){
                    $stoke = new Stoke();
                    $stoke->product_variable_id = $variable['product_variable_id'];
                    $stoke->unit_id = $variable['unit_id'];
                }
                $stoke->qty -= $variable['qty'];
                $stoke->save();
            }
        }

        return redirect()->route('admin.sales.index');
    }

    function saveVariable($index) {
        $selectedVariable = $this->data['variables'][$index] ?? NULL;

        $rules = [
            'product_variable_id' => 'required|exists:product_variables,id',
            'unit_id' => 'required|exists:units,id',
            'qty' => 'required|numeric',
            'sale_price' => 'required|numeric',
            'discount_type' => 'required|in:percentage,amount',
            'discount' => 'required|numeric',
        ];

        if(!$this->validator($selectedVariable,$rules)) return;

        if($selectedVariable['id']??false) {
            $newVar = $this->current->saleVariables()->find($selectedVariable['id']);
            $newVar->update($selectedVariable);
        }else{
            $newVar = $this->current->saleVariables()->create($selectedVariable);
        }


        $this->data['variables'][$index] = $newVar->toArray();

        $this->swal('Success','Variable saved successfully','success');
    }
    public function render()
    {
        $customers = Contact::customers()->get();
        $branches = Branch::active()->get();
        return view('livewire.tenant.sale.sale-actions',get_defined_vars());
    }
}
