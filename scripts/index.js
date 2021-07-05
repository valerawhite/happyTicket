$(document).ready(function() {

    $('#from').focusout(function(){
        let self = $(this);
        let error = (self).parent().parent().find('.error');
        if(self.val() < 100000) {
            if(!self.hasClass('not-valid')) {
                self.addClass('not-valid');
                error.css('display', 'block');
                error.html('This value must be > 100000');
            }
        } else {
            checkClassValid(self, error);
        }
    });
    $('#to').focusout(function(){
        let self = $(this);
        let error = (self).parent().parent().find('.error');
        if(self.val() > 999999 || self.val() <= 100000) {
            if(!self.hasClass('not-valid')) {
                self.addClass('not-valid');
                error.css('display', 'block');
                error.html('This value must be > 100000 and < 999999');
            }
        } else {
            checkClassValid(self, error);
        }
    });
    $('#from, #to').bind("change keyup input click", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $("#submit").on('click', function() {
        let data = $("#Ticketform").serializeArray();
        let from = $("#from");
        let to = $("#to");
        if(((from).hasClass('not-valid') || (to).hasClass('not-valid'))
            || (from.val() === '' || to.val() === '')) {
            return;
        }
        $.ajax({
            type: "POST",
            url: "HappyTicket.php",
            data: {min: data[0].value, max: data[1].value}
        }).done(function (msg) {
            msg = jQuery.parseJSON(msg);
            if(msg.success) {
                $("#result").html(msg.countHappyTickets);
            } else {
                $("#result").html(msg.message);
            }
        });
    });
});

function checkClassValid(self, error) {
    if(self.hasClass('not-valid')) {
        self.removeClass('not-valid');
        error.html('');
    }
}

