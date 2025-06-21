<?php

namespace App\Livewire\Tenant\Product;

use App\Models\Tenant\Brand;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use App\Models\Tenant\Tax;
use App\Models\Tenant\Unit;
use App\Traits\LivewireOperations;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductActions extends Component
{
    use LivewireOperations, WithFileUploads;
    public $id;

    public $model = Product::class;

    public $data = [
        'variables' => []
    ];
    public $current,$currentIndex;

    protected $rules = [
        'name' => 'required',
        'sku' => 'required|unique:products,sku',
        'description' => 'required',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'unit_id' => 'required|exists:units,id',
        'tax_id' => 'nullable|exists:taxes,id',
        'price_type' => 'required|in:inc_tax,ex_tax',
        'image' => 'required|image',
        'variables' => 'required|array',
        'variables.*.name' => 'required',
        'variables.*.sku' => 'required|unique:product_variables,sku',
        'variables.*.purchase_price_inc_tax' => 'required|numeric',
        'variables.*.purchase_price_ex_tax' => 'required|numeric',
        'variables.*.x_margin' => 'required|numeric',
        'variables.*.sell_price_inc_tax' => 'required_if:price_type,inc_tax|numeric',
        'variables.*.sell_price_ex_tax' => 'required_if:price_type,ex_tax|numeric',
        'variables.*.image' => 'nullable|image'
    ];

    // public $sku = '';

    function updatingDataType($value) {

        if($value == 'single'){
            $firstVariable = $this->data['variables'][0]??[];
            $this->data['variables'][] = [
                'name' => $this->current->name??$this->data['name']??"",
                'sku' => $this->current->sku??$this->data['sku']??"",
                'purchase_price_inc_tax' => $firstVariable['purchase_price_inc_tax']??0,
                'purchase_price_ex_tax' => $firstVariable['purchase_price_ex_tax']??0,
                'x_margin' => $firstVariable['x_margin']??0,
                'sell_price_inc_tax' => $firstVariable['sell_price_inc_tax']??0,
                'sell_price_ex_tax' => $firstVariable['sell_price_ex_tax']??0,
                'image' => NULL
            ];
        }
    }

    public function mount()
    {
        $this->current = $this->model::find($this->id);

        if($this->current) {
            $this->data = [
                'name' => $this->current->name,
                'sku' => $this->current->sku,
                'weight' => $this->current->weight,
                'alert_qty' => $this->current->alert_qty,
                'type' => $this->current->type,
                'description' => $this->current->description,
                'category_id' => $this->current->category_id,
                'brand_id' => $this->current->brand_id,
                'unit_id' => $this->current->unit_id,
                'tax_id' => $this->current->tax_id,
                'image' => $this->current->image_path,
                'variables' => $this->current->productVariables->map(function ($q) {
                    return [
                        'id'=>$q->id,
                        'sku' => $q['sku'],
                        'name' => $q['name'],
                        'purchase_price_inc_tax' => $q['purchase_price_inc_tax'],
                        'purchase_price_ex_tax' => $q['purchase_price_ex_tax'],
                        'x_margin' => $q['x_margin'],
                        'stoke_total_qty' => $q->stoke_total_qty,
                        'sell_price_inc_tax' => $q['sell_price_inc_tax'],
                        'sell_price_ex_tax' => $q['sell_price_ex_tax'],
                        'image' => null
                    ];
                })->toArray()
            ];
        }
    }

    function addNewVariable() {
        $this->data['variables'][] = [
            'sku' => '',
            'name' => '',
            'purchase_price_inc_tax' => 0,
            'purchase_price_ex_tax' => 0,
            'x_margin' => 0,
            'sell_price_inc_tax' => 0,
            'sell_price_ex_tax' => 0,
            'image'=> NULL,
            'stoke_total_qty' => 0
        ];
    }

    function removeVariable() {
        $choosed = $this->data['variables'][$this->currentIndex];

        if($choosed['id']??false) {
            $this->model::find($choosed['id'])->delete();
        }

        if(isset($this->data['variables'][$this->currentIndex])) {
            unset($this->data['variables'][$this->currentIndex]);
        }

        $this->dismiss();
    }

    public function save()
    {
        if(!$this->validator()) return;

        if($this->current) {
            $this->current->update($this->data);

            if($this->data['image'] && !is_string($this->data['image'])) {
                $this->current->image()->delete();

                $this->current->image()->create([
                    'path' => $this->data['image'],
                    'key' => 'image'
                ]);
            }

        } else {
            $this->current = $this->model::create($this->data);

            $this->current->image()->create([
                'path' => $this->data['image'],
                'key' => 'image'
            ]);

            foreach($this->data['variables'] as $variable) {
                $var = $this->current->productVariables()->create($variable);

                if($variable['image']){
                    $var->image()->create([
                        'path' => $variable['image'],
                        'key' => 'image'
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index');
    }

    function saveVariable($index) {
        $selectedVariable = $this->data['variables'][$index] ?? NULL;

        $rules = [
            'name' => 'required',
            'sku' => 'required|unique:product_variables,sku,'.($selectedVariable['id']??NULL),
            'purchase_price_inc_tax' => 'required|numeric',
            'purchase_price_ex_tax' => 'required|numeric',
            'x_margin' => 'required|numeric',
            'sell_price_inc_tax' => 'required_if:price_type,inc_tax|numeric',
            'sell_price_ex_tax' => 'required_if:price_type,ex_tax|numeric',
            'image' => 'nullable|image'
        ];

        // dd($rules);

        if(!$this->validator($selectedVariable,$rules)) return;

        if($selectedVariable['id']??false) {
            $newVar = $this->current->productVariables()->find($selectedVariable['id']);
            $newVar->update($selectedVariable);
        }else{
            $newVar = $this->current->productVariables()->create($selectedVariable);
        }


        $this->data['variables'][$index] = $newVar->toArray();

        if($selectedVariable['image'] && !is_string($selectedVariable['image'])) {
            $newVar->image()->create([
                'path' => $selectedVariable['image'],
                'key' => 'image'
            ]);
        }

        $this->swal('Success','Variable saved successfully','success');
    }

    public function render()
    {
        $units = Unit::parentOnly()->get();
        $categories = Category::parentOnly()->with('children')->get();
        $taxes = Tax::all();
        $brands = Brand::active()->get();

        return view('livewire.tenant.product.product-actions',get_defined_vars());
    }
}
