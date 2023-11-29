<b>
    {{ __('texts.texts.classes.' . auth()->user()->lang) }}
</b>

<div class="mt-2">
    <div class="row">
        @foreach ($classes as $class_)
            <div class="col-md-4 p-2">
                <div class="p-3 bg-white rounded border">
                    {{ $class_->class_->name }}
                    <br>
                    <small>
                        #{{ $class_->role }}
                    </small>
                </div>
            </div>
        @endforeach
    </div>
</div>
