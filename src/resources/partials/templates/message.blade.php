@php
$message->users()->syncWithoutDetaching(auth()->user())
@endphp
<li class="media {{ (auth()->id() == $message->user_id) ? 'sent' : 'd-flex received' }}">
    @if((auth()->id() != $message->user_id) )
    <div class="avatar">
        <img src="{{$message->user->getProfileImage()}}" alt="User Image" class="avatar-img rounded-circle">
    </div>
    @endif
    <div class="media-body">
        <div class="msg-box">
            <div>
                @if ($message->attachment)
                <div class="chat-msg-attachments">
                    <div class="chat-attachment">
                        @if(str_contains($message->mime_type,'image'))
                        <img src="{{asset($message->attachment)}}" alt="Attachment">

                        @else
                        <video controls>
                            <source src="{{asset($message->attachment)}}" type="{{$message->mime_type}}">
                        </video>
                        @endif
                    </div>
                </div>
                @endif
                @if ($message->message)
                <p>{!!$message->message!!}</p>
                @endif
                <ul class="chat-msg-info">
                    <li>
                        <div class="chat-time">
                            @if($message->attachment)
                            <span><a href="{{route('laravel.messenger.download.attachment',$message->id)}}" target="_blank">
                                    <i class="fa fa-download"></i>
                                </a></span>
                            @endif
                            <span>
                                {{$message->created_at->format('h:i a')}}
                                @if((auth()->id() == $message->user_id))
                                <i class="fas fa-check-double @if($message->is_read) is_message_read @endif"></i>
                                @endif
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</li>
