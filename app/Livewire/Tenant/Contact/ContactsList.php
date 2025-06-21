<?php

namespace App\Livewire\Tenant\Contact;

use App\Models\Tenant\Contact;
use App\Traits\LivewireOperations;
use Livewire\Component;

class ContactsList extends Component
{
    use LivewireOperations;

    public $id;

    public $current;
    public $data = [
        'active'=>0
    ];

    public $model = Contact::class;

    public $rules = [
        'name' => 'required',
        'phone'=>'required',
        'email'=> 'required',
        'address'=>'required'
    ];

    function setCurrent($id) {
        $this->current = $model = $this->model::find($id);

        $this->data = [
            'name'=>$model?->name ?? "",
            'phone'=>$model?->phone ?? "",
            'email'=>$model?->email ?? "",
            'address'=>$model?->address ?? "",
            'active'=>($model?->active ?? 0) == 1,
        ];
    }

    function save() {

        if(!$this->validator()) return;


        $model = $this->current;

        if(!$model){
            $model = new $this->model();
            $model->type = $this->id;
        }

        $model->name = $this->data['name'];
        $model->email = $this->data['email'];
        $model->phone = $this->data['phone'];
        $model->address = $this->data['address'];
        $model->active = (bool)$this->data['active'] ?? 0;
        $model->save();

        $this->swal('Success!','Saved Successfully!','success');
        $this->dismiss();
    }

    function delete() {
        $this->current->delete();
        $this->swal('Success!','Deleted Successfully!','success');
        $this->dismiss();
    }

    public function render()
    {
        $contacts = $this->model::whereType($this->id);

        $contacts = $contacts->paginate(20);

        return view('livewire.tenant.contact.contacts-list',get_defined_vars());
    }
}
