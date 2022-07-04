<div class="list-group">
    @forelse($all_events as $event)
            <p class="text-center txt-info">Изменения связанные с этой игрой: </p>
            <div class="list-group-item list-group-item-action" class="rounded-circle" class="list-group-item list-group-item-action">
                <dl class="row">
                    <dt class="col-sm-3 text-right">Имя</dt>
                    <dd class="col-sm-9">{{ $event->user->name }}</dd>
                    <dt class="col-sm-3 text-right">Действие</dt>
                    <dd class="col-sm-9">{{ $event->admin->name }}: {{ $event->description }}</dd>
                    <dt class="col-sm-3 text-right">Дата</dt>
                    <dd class="col-sm-9">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $event->created_at)->setTimezone('Europe/Kiev')->format('d.m.Y H:i') }}</dd>
                </dl>
            </div>
    @empty
        <p class="text-center txt-warning">Нет изменений связанныx с этой игрой: </p>
    @endforelse
</div>