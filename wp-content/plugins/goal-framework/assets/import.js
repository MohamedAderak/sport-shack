jQuery(document).ready(function($){
    var source = '';
    
    if ( $('.goal-btn-import').data('disabled') ) {
        $(this).attr('disabled', 'disabled');
    }
    
    $('.goal-btn-import').click(function(){
        // all
        source = $('.goal-demo-import-wrapper .source-data').val();
        if ( confirm('Do you want to import demo now ?') ) {
            
            $(this).attr('disabled', 'disabled');
            $('.goal-progress-content').show();
            
            $('.first_settings span').hide();
            $('.first_settings .installing').show();
            $('.steps li').removeClass('active');
            $('.first_settings').addClass('active');

            goal_import_type('first_settings');
        }
    });

    function goal_import_type( type ) {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'goal_import_sample',
                demo_source: source,
                ajax: 1,
                import_type: type
            },
            dataType: 'json',
            success: function (res) {
                var next = res.next;

                if ( res.status == false ) {
                    goal_import_error( res );
                    return false;
                }

                if ( next == 'done' ) {
                    goal_import_complete( type );
                    return false;
                }
                
                if ( next == 'error' ) {
                    goal_import_error( res );
                    return false;
                }

                goal_import_complete_step( type, res );
                goal_import_type( next );
            },
            error: function (html) {
                $('.goal_progress_error_message .goal-error .content').append('<p>' + html + '</p>');
                $('.goal_progress_error_message').show();
                return false;
            }
        });

        return false;
    }

    function goal_import_complete_step(type, res) {
        $( '.' + type + ' span' ).hide();
        $( '.' + type + ' .installed' ).show();

        var next = res.next;
        if ( next == 'done' ) {
            $('.goal-complete').show();
            $('.steps li').removeClass('active');
        } else {
            $('.' + next + ' span').hide();
            $('.' + next + ' .installing').show();
            $('.steps li').removeClass('active');
            $('.' + next).addClass('active');
        }
    }

    function goal_import_complete(type) {
        $( '.' + type + ' span' ).hide();
        $( '.' + type + ' .installed' ).show();
        $('.goal-complete').show();
    }

    function goal_import_error(res) {
        if ( res.msg !== undefined && res.msg != '' ) {
            $('.goal_progress_error_message .goal-error .content').append('<p>' + res.msg + '</p>');
            $('.goal_progress_error_message').show();
        }
    }

});


