var turnOffCustomStylePoint = 767;

var supportOptions = {

  responsiveAt: turnOffCustomStylePoint,

  onFail: function() {
    alert( $support.getInvalid().length +' invalid fields.' )
  },
  onFail: function(){
    // Form does NOT validate
  },

  inputs: {
      'name': {
          filters: 'required name',
          data: {
            //ajax: { url:'validate.php' }
          }
      },
      'email': {
          filters: 'required email max',
          data: { max: 50 }
      }

  }
};


var $support = $('#support').idealforms(supportOptions).data('idealforms');
$('#reset').click(function(){ $support.reset().fresh().focusFirst() });
$support.focusFirst();