
<li class="media mt-4 mb-4">
  <div class="media-body">
     <a href="{{ route('users.show', $user->id )}}">

    <h5 class="mt-0 mb-1">{{ $user->name }} <small> / {{ $status->created_at->diffForHumans() }}</small></h5>
    {{ $status->content }}
    </a>
  </div>
</li>
