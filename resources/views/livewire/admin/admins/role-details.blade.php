<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <!-- Role Details Panel -->
            <div class="panel panel-primary shadow-sm">
                <div class="panel-heading text-center">
                    <h3 class="panel-title" style="font-size:18px; font-weight:600;">
                        <i class="fa fa-lock"></i> Role Details
                    </h3>
                </div>

                <div class="panel-body">
                    <!-- Role Name -->
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="roleName" class="col-sm-3 control-label text-right">
                                Role Name
                            </label>
                            <div class="col-sm-9">
                                <input type="text"
                                       class="form-control input-sm"
                                       id="roleName"
                                       name="roleName"
                                       wire:model.lazy="roleName"
                                       placeholder="Enter role name">
                            </div>
                        </div>
                    </div>

                    <hr style="margin: 25px 0; border-top: 1px solid #ddd;">

                    <!-- Permissions List Panel -->
                    <div class="panel panel-default">
                        <div class="panel-heading" style="background-color:#f5f5f5;">
                            <h4 class="panel-title" style="font-size:16px; font-weight:600;">
                                <i class="fa fa-list-ul"></i> Permissions List
                            </h4>
                        </div>

                        <div class="panel-body" style="background:#fafafa;">
                            <div class="panel-group" id="permissionsAccordion">

                                @foreach ($permissionsList as $key => $list)
                                @php $collapseId = 'collapse_' . $key; @endphp

                                <div class="panel panel-info">
                                    <div class="panel-heading" style="cursor:pointer;">
                                        <h4 class="panel-title" style="margin:0;">
                                            <a data-toggle="collapse"
                                               data-parent="#permissionsAccordion"
                                               href="#{{ $collapseId }}"
                                               aria-expanded="true"
                                               style="display:block; text-decoration:none; color:#222; font-weight:600;">
                                                {{ __(ucwords(str_replace('_', ' ', $key))) }}
                                            </a>
                                        </h4>
                                    </div>

                                    <!-- Panel Body -->
                                    <div id="{{ $collapseId }}" class="panel-collapse collapse">
                                        <div class="panel-body" style="padding:15px 25px;">
                                            <div class="row">
                                                @foreach ($list as $per)
                                                <div class="col-sm-6 col-md-4">
                                                    <label style="font-weight:normal;">
                                                        <input type="checkbox"
                                                               wire:click="setPermission('{{ $per }}', $event.target.checked)"
                                                               id="{{ $per }}"
                                                               {{ ($permissions[$per] ?? false) ? 'checked' : '' }}>
                                                        {{ __(ucwords(str_replace(['_', $key], [' ', ''], $per))) }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="text-center" style="margin-top:25px;">
                        <button class="btn btn-success btn-sm" wire:click="save">
                            <i class="fa fa-save"></i> Save Role
                        </button>
                        <button type="reset" class="btn btn-default btn-sm" style="margin-left:10px;">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
.shadow-sm {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.panel-info > .panel-heading {
    background-color: #f0f8ff;
    border-color: #bce8f1;
}
.panel-info .panel-title strong {
    color: #31708f;
}
.panel-body label {
    cursor: pointer;
}
</style>
@endpush
