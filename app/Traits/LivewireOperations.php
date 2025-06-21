<?php


namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait LivewireOperations {
    function swal($title,$message,$type = 'success') {
        $this->dispatch('alert-message',$this->_swal($title,$message,$type));
    }

    function _swal($title,$message,$type) {
        return ['message'=>$message,'title'=>$title,'type'=>$type];
    }

    function dismiss() {
        $this->dispatch('modal-dismiss');
    }

    function validator($data=null,$rules=null) {
        $validator = Validator::make($data??$this->data,$rules??$this->rules);

        if($validator->fails()){
            $this->swal('Error!',$validator->errors()->first(),'error');
            return false;
        }

        return true;
    }
}
