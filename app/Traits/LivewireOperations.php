<?php


namespace App\Traits;

use App\Models\File;
use Illuminate\Support\Facades\Validator;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

trait LivewireOperations {
    use WithFileUploads;

    function swal($title,$message,$type = 'success') {
        $this->dispatch('alert-message',$this->_swal($title,$message,$type));
    }

    function _swal($title,$message,$type) {
        return ['message'=>$message,'title'=>$title,'type'=>$type];
    }

    function deleteSwal() {
        $this->alert('success','Deleted successfully');
    }

    function alert($type,$title,$position = 'top-end') {
        LivewireAlert::title(title: ucfirst($type))->text($title)->{$type}()
        ->toast()
        ->position($position)
        ->show();
    }

    function popup($type,$title,$position = 'center') {
        LivewireAlert::title(title: ucfirst($type))->text($title)->{$type}()
        ->position($position)
        ->show();
    }

    function confirm($confirmEvent,$type = 'success',$title,$text = '',$confirmBtnText,$cancelBtnText = 'Cancel') {
        LivewireAlert::title($title)
        ->text($text)
        ->asConfirm()
        ->{$type}()
        ->withConfirmButton($confirmBtnText)
        ->withDenyButton($cancelBtnText)
        ->onConfirm($confirmEvent)
        ->show();
    }

    function deleteAlert($confirmEvent = 'delete') {
        LivewireAlert::title('Are you sure you want to delete?')
        ->asConfirm()
        ->withConfirmButton('Delete')
        ->withDenyButton('Cancel')
        ->onConfirm($confirmEvent)
        ->show();
    }

    function updateSwal() {
        $this->alert('success','Updated successfully');
    }

    function dismiss() {
        $this->dispatch('modal-dismiss');
    }

    /**
     * Opens a modal with a given selector.
     *
     * @param string $selector
     * @return void
     */
    function openModal($selector) {
        $this->dispatch('open-modal',$selector);
    }


    function validator($data = null,$rules = null) {
        $validator = Validator::make($data ?? $this->data,$rules ?? $this->rules);

        if($validator->fails()){
            $this->alert('error',$validator->errors()->first());
            return false;
        }

        return true;
    }

    function validator2($data = null,$rules = null) {
        $validator = Validator::make($data ?? $this->data,$rules ?? $this->rules);

        if($validator->fails()){
            $this->popup('error',$validator->errors()->first());
            return false;
        }

        return true;
    }


    function removeFile($id) {

        if(is_string($id)){
            if($this->data ?? false){
                foreach ($this->data as $key => $value) {
                    if($value instanceof TemporaryUploadedFile){
                        if($id == $value->temporaryUrl()){
                            $this->data[$key] = null;
                        }
                    }
                }
            }
        }else{
            File::find($id)->delete();
        }

        $this->alert('success', 'تم الحذف بنجاح');
        if($this->current){
            $this->current = $this->current->fresh();
        }
    }

    function redirectWithTimeout($url, $timeout = 500) {
        $this->js('window.setTimeout(function(){ window.location = "'.url($url).'"; },'.$timeout.');');
    }

    function redirectToDownload($url) {

        $this->export = null;
        $this->dispatch('download-file', [
            'url' => route('admin.export.download', ['file' => $url]),
        ]);
    }

    function resetFilters() {
        $this->reset('filters');
    }
}
