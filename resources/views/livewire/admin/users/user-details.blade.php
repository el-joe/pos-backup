<div>
<div class="white-box position-relative">
    @if(!!$user->active)
        <span class="badge badge-success position-absolute" style="top:30px; right:10px;">Active</span>
    @else
        <span class="badge badge-danger position-absolute" style="top:30px; right:10px;">Not Active</span>
    @endif
        <h3 class="box-title">{{ $user->name }}'s Profile</h3>
        <!-- Nav tabs -->
        <ul class="nav customtab nav-tabs" role="tablist">
            <li role="presentation" class="{{ $activeTab === 'details' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'details')" href="#home1" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Details</span></a></li>
            <li role="presentation" class="{{ $activeTab === 'accounts' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'accounts')" href="#profile1" aria-controls="profile" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Accounts</span></a></li>
            <li role="presentation" class="{{ $activeTab === 'transactions' ? 'active' : '' }}"><a wire:click="$set('activeTab', 'transactions')" href="#messages1" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-exchange-horizontal"></i></span> <span class="hidden-xs">Transactions</span></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'details' ? 'in active' : '' }}" id="home1">
                <div class="row">
                    <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                        <br>
                        <p class="text-muted">{{ $user->name }}</p>
                    </div>
                    <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                        <br>
                        <p class="text-muted">{{ $user->phone }}</p>
                    </div>
                    <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                        <br>
                        <p class="text-muted">{{ $user->email }}</p>
                    </div>
                    <div class="col-md-3 col-xs-6"> <strong>Address</strong>
                        <br>
                        <p class="text-muted">{{ $user->address }}</p>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'accounts' ? 'in active' : '' }}" id="profile1">
                @livewire('admin.accounts.accounts-list',['subPage'=>true,'filters'=>[
                    'model_type' => \App\Models\Tenant\User::class,
                    'model_id' => $user->id
                ]])
                <div class="clearfix"></div>
            </div>
            <div role="tabpanel" class="tab-pane fade {{ $activeTab === 'transactions' ? 'in active' : '' }}" id="messages1">
                <div class="col-md-6">
                    <h3>Come on you have a lot message</h3>
                    <h4>you can use it with the small code</h4> </div>
                <div class="col-md-5 pull-right">
                    <p>Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a.</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .position-relative {
            position: relative; /* make parent relative */
        }

        .position-absolute {
            position: absolute;
            padding: 6px 14px;   /* more padding for UX */
        }
    </style>
@endpush
