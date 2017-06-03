<a href="#top" class="pull-right to_top hide">
    <i class="glyphicon glyphicon-menu-up"></i>  </a>

<div class="modal fade" role="dialog" id="open_subscribe">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="modal-title h3" id="exampleModalLabel">Заявка на подписку</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="subscribe"  method = "post" action="{{asset('/subscribe')}}">
                    {{csrf_field()}}
                    <input type="hidden" class="form-control" name="price_id" id="price_id">
                    <div class="form-group">
                        <label for="name">Имя и фамилия:</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Введите имя и фамилию" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name='email' id = "email" class="form-control" placeholder="Введите Email" required>
                    </div>
                    <div class="form-group">
                        <div  class ='alert alert-warning hide' id = "error">

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-success" onclick="subscribe(this);">Отправить</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" role="dialog" id ="success">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <p class="h4 text-center"> Подписка оформлена! </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>



</div>

<script>

$(document).ready(function(){

        $("a[href='#top']").click(function() {
            $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;
        });
    });

    $('[data-toggle="popover"]').popover({
        placement : 'right'
    });

    $('[data-toggle="popover_left"]').popover({
        placement : 'left',
    });

    $( ".price" ).hover(
        function() {
            var id = $(this).attr('id');
            $('#popover_'+ id).click();
            old_price = $("#price_" + id).html();
            var new_price = parseInt(old_price, 10) * 0.9;
            $("#price_" + id).html(new_price + "грн");
        }

    );
    /*
    $( ".price" ).mouseleave(
        function() {
            var url = window.location.pathname +'price';
            var id = $(this).attr('id');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'POST',
                data: {'id': id},
                type: "JSON",
                dataType: 'JSON',
                success: function (responce) {
                    $("#price_" + id).html(responce.price);
                },
                error: function (responce) {
                }
            });
        }
    );*/
    $(window).scroll(function() {
        if($(document).scrollTop() > 200) {
            $("a[href='#top']").removeClass('hide');
        }
        if($(document).scrollTop() < 200) {
            $("a[href='#top']").addClass('hide');
        }
    });


    window.onbeforeunload = function(e) {
        return 'Вы уверены, что хотите закрыть страницу?';
    };

    var modalOptions = {
        opacity: 75,
        onClose: function(dialog) { overlayHide(365); }
    }
    setTimeout(function(){

        $('#open_subscribe').modal(modalOptions);


    }, 15000)
    function subscribe(el) {
        event.preventDefault();
        $(el).prop( 'disabled', true );
        var form = $('#subscribe');
        dataForm = form.serialize();
        var url = $(form).attr('action');
        $.ajax({
            url: url,
            method: 'POST',
            data: dataForm,
            type:"JSON",
            dataType: 'JSON',
            success: function (responce) {
                $('#open_subscribe').modal('hide');
                if( ! $('#error').hasClass('hide')){
                    $('#error').addClass('hide');
                }
                $('#name').val('');
                $('#email').val('');
                $('#success').modal('show');
                $(el).prop( 'disabled', false );
            },
            error: function (responce){
                var errors;
                errors = responce.responseJSON.errors;
                if(errors == null){
                    errors = "Не удалось отправить заявку, пожалуйста, попробуйте еще раз.";
                }
                $( "#submit" ).attr( "data-content", errors );
                $('#submit').popover('show');
                $('#error').html(
                    errors
                );
                $('#error').removeClass('hide');
                $(el).prop( 'disabled', false );
            }
        });
    };

    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parent().siblings().removeClass('open');
        $(this).parent().toggleClass('open');
    });
</script>
</body>
</html>