<div class="chat-header">
    <a id="back_user_list" href="javascript:void(0)" class="back-user-list">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </a>
    <div class="media d-flex">
        <div class="media-body">
            <div class="user-name"> {!! $chat->getDisplayIcon() !!} {{$chat->getTitle()}}</div>
        </div>
    </div>
    <div class="chat-options d-none">
        <a href="javascript:void(0)">
            <i class="fa fa-phone" aria-hidden="true"></i>
        </a>
        <a href="javascript:void(0)">
            <i class="fa fa-video-camera" aria-hidden="true"></i>
        </a>
        <a href="javascript:void(0)">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        </a>
    </div>
</div>
<div class="chat-body">
    <div class="chat-scroll">
        <ul class="list-unstyled">
            @foreach ($messages as $message)
            @include('laravel-messenger::partials.templates.message')
            @endforeach
        </ul>
    </div>
</div>
