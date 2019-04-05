<div class="card mt-3">
    <ul class="list-reset text-xs">
        @foreach ($project->activity as $activity)
            <li class="{{ $loop->last ? '' : 'mb-1' }}">
                @include ("projects.activity.{$activity->description}")

                <p class="text-grey">{{ $activity->created_at->diffForHumans(null, true) }}</p>
            </li>
        @endforeach
    </ul>
</div>