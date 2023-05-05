<div id="addChatModal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{__trans('create_chat')}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="ajax-create-new-chat">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="field-1" class="form-label">{{__trans('select_user')}}</label>
                                <select name="user_id" id="user_id" class="form-control select-search" style="width:100%" required>
                                    <option value="">{{__trans('select_user')}}</option>
                                    @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="field-1" class="form-label">{{__trans('message')}}</label>
                                <textarea name="message" id="message" class="form-control" cols="30" rows="10" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">{{__trans('close')}}</button>
                    <button type="submit" class="btn btn-info waves-effect waves-light">{{__trans('send_message')}} </button>
                </div>
            </form>
        </div>
    </div>
</div>
