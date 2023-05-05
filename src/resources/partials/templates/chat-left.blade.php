<div class="chat-header">
    <img src="{{getUserAvatar(auth()->user()->name)}}" width="50" alt="User Avatar">
    <a href="" class="chat-compose" data-bs-toggle="modal" data-bs-target="#addChatModal">
        <i class="material-icons">control_point</i>
    </a>
</div>
<?php

?>
<div class="chat-users-list">
    <div class="chat-scroll">
        @foreach ($chats as $chat)
        <?php
        $isActive = false;
        $hasMessage = $chat->messages()->exists();
        $lastMessage = $chat->lastMessage();
        $unreadMessageCount = $chat->UnreadMessageCount();
        if ($active_chat_id) {
            if ($active_chat_id == $chat->id) {
                $isActive = true;
            }
        } else {
            if ($loop->iteration == 1) {
                $isActive = true;
            }
        }
        ?>
        <a href="javascript:void(0);" class="media d-flex @if($isActive) active @endif all-chats" data-id="{{$chat->id}}">
            <div class="media-img-wrap">
                {!!$chat->getDisplayIcon()!!}
            </div>
            <div class="media-body">
                <div>
                    <div class="user-name">{{$chat->getTitle()}}</div>
                    @if( $hasMessage)
                    @if($lastMessage->message !='')
                    <div class="user-last-chat">{!! shorterText($lastMessage->message,15)!!}</div>
                    @else
                    <div class="user-last-chat"><i class="fa fa-paperclip"></i> Attachment </div>
                    @endif
                    @endif
                </div>
                @if($hasMessage)
                <div>
                    <div class="last-chat-time block">{{$lastMessage->created_at->format('h:i a')}}</div>
                    @if( $unreadMessageCount )
                    <div class="badge badge-success badge-pill">{{ $unreadMessageCount }}</div>
                    @endif
                </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>
