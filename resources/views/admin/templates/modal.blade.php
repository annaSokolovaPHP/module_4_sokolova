<div class="modal fade">
    <div class="modal-dialog {{size}}">    // {{size}} - переменная для динамического изменения модального окна
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">{{title}}</h4>   //название модели
            </div>
            <div class="modal-body">
                {{yield}}                                       // сюда будем грузить уникальный контент модального окна
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" {{action 'save'}}>Сохранить изменения</button>
            </div>
        </div>
    </div>
</div>