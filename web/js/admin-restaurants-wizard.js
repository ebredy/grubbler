/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2015 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 * 
 */
(function(document, window, $) {
  'use strict';
  
  var TIME_PATTERN = /^(09|1[0-7]{1}):[0-5]{1}[0-9]{1}$/;
  
  var Site = window.Site;

  $(document).ready(function($) {
    Site.run();
  });
   function post(param, url){
        return $.ajax({
            type : 'POST',
            data : param,
            url  : url,
            dataType: "json"
        });

    }
  // Example Wizard Form
  // -------------------
  (function() {
    // set up formvalidation
    $('#restaurantForm').formValidation({
      framework: 'bootstrap',
      fields: {
        restaurant: {
          validators: {
            notEmpty: {
              message: 'The restaurant name is required'
            },
            stringLength: {
              min: 3,
              max: 100,
              message: 'The restaurant must be more than 3 and less than 100 characters long'
            }
//            ,
//            regexp: {
//              regexp: /^[a-zA-Z0-9_\.]+$/,
//              message: 'The username can only consist of alphabetical, number, dot and underscore'
//            }
          }
        },
        phone: {
            validators: {
                notEmpty: {
                    message: 'The  phone number is required'
                },
                regexp: {
                    regexp: /^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/,
                    message: 'The  phone number is not valid format: (999) 999-9999 e.g. (203) 444-9245'
                }
            }
        },
        fax: {
            validators: {
                notEmpty: {
                    message: 'The  fax number is required'
                },
                regexp: {
                    regexp: /^(\+\d{1,2}\s)?\(?\d{3}\)?[\s.-]\d{3}[\s.-]\d{4}$/,
                    message: 'The  fax number is not valid format: (999) 999-9999 e.g. (203) 444-9245'
                }
            }
        },        
        address: {
                    validators: {
                        notEmpty: {
                            message: 'The address is required'
                        }
                    }
                },
        city_id: {
            validators: {
                notEmpty: {
                    message: 'The city  is required'
                }
            }
        },
        state_id: {
            validators: {
                notEmpty: {
                    message: 'The city  is required'
                }
            }
        },        
        zipcode: {
            validators: {
                notEmpty: {
                    message: 'The  zip code is required'
                },
                regexp: {
                    regexp: /(^\d{5}$)|(^\d{5}-\d{4}$)/,
                    message: 'The zip code  is not valid acceptable formats are either 10805 or 10805-1234'
                }
            }
        },
        opens: {
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'The open time is required'
                    },
                    regexp: {
                        regexp: TIME_PATTERN,
                        message: 'The open time must be between 09:00 and 17:59'
                    },
                    callback: {
                        message: 'The open time must be earlier then the close time',
                        callback: function(value, validator, $field) {
                            var endTime = validator.getFieldElements('closes').val();
                            if (endTime === '' || !TIME_PATTERN.test(endTime)) {
                                return true;
                            }
                            var startHour    = parseInt(value.split(':')[0], 10),
                                startMinutes = parseInt(value.split(':')[1], 10),
                                endHour      = parseInt(endTime.split(':')[0], 10),
                                endMinutes   = parseInt(endTime.split(':')[1], 10);

                            if (startHour < endHour || (startHour == endHour && startMinutes < endMinutes)) {
                                // The end time is also valid
                                // So, we need to update its status
                                validator.updateStatus('closes', validator.STATUS_VALID, 'callback');
                                return true;
                            }

                            return false;
                        }
                    }
                }
            },
            closes: {
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'The close time is required'
                    },
                    regexp: {
                        regexp: TIME_PATTERN,
                        message: 'The close time must be between 09:00 and 17:59'
                    },
                    callback: {
                        message: 'The close time must be later then the start one',
                        callback: function(value, validator, $field) {
                            var startTime = validator.getFieldElements('opens').val();
                            if (startTime == '' || !TIME_PATTERN.test(startTime)) {
                                return true;
                            }
                            var startHour    = parseInt(startTime.split(':')[0], 10),
                                startMinutes = parseInt(startTime.split(':')[1], 10),
                                endHour      = parseInt(value.split(':')[0], 10),
                                endMinutes   = parseInt(value.split(':')[1], 10);

                            if (endHour > startHour || (endHour == startHour && endMinutes > startMinutes)) {
                                // The start time is also valid
                                // So, we need to update its status
                                validator.updateStatus('opens', validator.STATUS_VALID, 'callback');
                                return true;
                            }

                            return false;
                        }
                    }
                }
            },        
        delivery_radius: {
            validators: {
                notEmpty: {
                    message: 'The delivery radius is required'
                },
                digits: {
                    message: 'the delivery radius must be whole digits'
                }                
            }
        },
        latitude: {
            validators: {
                notEmpty: {
                    message: 'The latitude is required'
                },
                between: {
                    min: -90,
                    max: 90,
                    message: 'The latitude must be between -90.0 and 90.0'
                },                        
                regexp: {
                        regexp: /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/,
                        message: 'the latitude must be floating number example 100045.1234'
                }                
            }
        },
        longitude: {
            validators: {
                notEmpty: {
                    message: 'The longitude is required'
                },
                between: {
                    min: -180,
                    max: 180,
                    message: 'The longitude must be between -180.0 and 180.0'
                },                        
                regexp: {
                    regexp: /^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}/,
                    message: 'the longitude must be floating number example 100045.1234'
                }                 
            }
        },    
        rating: {
            validators: {
                notEmpty: {
                    message: 'The rating is required'
                },
                between: {
                    min: 1,
                    max: 5,
                    message: 'The rating must be between 1 and 5'
                }                
            }
        },                 
        // These fields will be validated when being visible
        price: {
            validators: {
                notEmpty: {
                    message: 'The price is required'
                },
                between: {
                    min: 1,
                    max: 5,
                    message: 'The price must be between 1 and 5'
                }                           
            }
        },
        full_address: {
            trigger: 'blur',
            validators: {
                notEmpty: {
                    message: 'The full address is required'
                },
                callback: {
                    message: 'invalid address or unable to get coordinates',
                    callback: function(value, validator, $field) {
                        if(post({'full_address': value},'/gadmin/getcoordinates').done(function(response){
                        
                                            if(response.code !=200){
                                                return false;
                                            }
                                            $("#longitude").val(response.data.longitude);
                                            $("#latitude").val(response.data.latitude);
                                            return true;
                    })){
                            validator.updateStatus('longitude', validator.STATUS_VALID, 'callback');
                            validator.updateStatus('latitude', validator.STATUS_VALID, 'callback');
                    }
                    }
                }
            }
        }

       
    }
        
                       
});

    $("#exampleBillingForm").formValidation({
      framework: 'bootstrap',
      fields: {
        number: {
          validators: {
            notEmpty: {
              message: 'The credit card number is required'
            }
            // creditCard: {
            //   message: 'The credit card number is not valid'
            // }
          }
        },
        cvv: {
          validators: {
            notEmpty: {
              message: 'The CVV number is required'
            }
            // cvv: {
            //   creditCardField: 'number',
            //   message: 'The CVV number is not valid'
            // }
          }
        }
      }
    });
    $('#menuForm').formValidation({
      framework: 'bootstrap',
      fields: {
        uploadImage: {
          validators: {
            notEmpty: {
              message: 'The upload image is required'
            }

          }
        },
        item: {
            validators: {
                notEmpty: {
                    message: 'The  item name is required'
                }
            }
        },
        description: {
            validators: {
                notEmpty: {
                    message: 'The  description is required'
                }
            }
        },
        price: {
            validators: {
                notEmpty: {
                    message: 'The  price is required'
                },
                regexp: {
                    regexp: /^\$?\d+(,\d{3})*(\.\d*)?$/,
                    message: 'Money format only'
                }
            }
        }                
      }
    });
  $('#menuUploadForm').fileupload({
    url: '../../server/fileupload/',
    dropzone: $('#foodImage'),
    filesContainer: $('.file-list'),
    uploadTemplateId: false,
    downloadTemplateId: false,
    uploadTemplate: tmpl(
      '{% for (var i=0, file; file=o.files[i]; i++) { %}' +
      '<div class="file template-upload fade col-lg-2 col-md-4 col-sm-6 {%=file.type.search("image") !== -1? "image" : "other-file"%}">' +
      '<div class="file-item">' +
      '<div class="preview vertical-align">' +
      '<div class="file-action-wrap">' +
      '<div class="file-action">' +
      '{% if (!i && !o.options.autoUpload) { %}' +
      '<i class="icon wb-upload start" data-toggle="tooltip" data-original-title="Upload file" aria-hidden="true"></i>' +
      '{% } %}' +
      '{% if (!i) { %}' +
      '<i class="icon wb-close cancel" data-toggle="tooltip" data-original-title="Stop upload file" aria-hidden="true"></i>' +
      '{% } %}' +
      '</div>' +
      '</div>' +
      '</div>' +
      '<div class="info-wrap">' +
      '<div class="title">{%=file.name%}</div>' +
      '</div>' +
      '<div class="progress progress-striped active" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" role="progressbar">' +
      '<div class="progress-bar progress-bar-success" style="width:0%;"></div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '{% } %}'
    ),
    downloadTemplate: tmpl(
      '{% for (var i=0, file; file=o.files[i]; i++) { %}' +
      '<div class="file template-download fade col-lg-2 col-md-4 col-sm-6 {%=file.type.search("image") !== -1? "image" : "other-file"%}">' +
      '<div class="file-item">' +
      '<div class="preview vertical-align">' +
      '<div class="file-action-wrap">' +
      '<div class="file-action">' +
      '<i class="icon wb-trash delete" data-toggle="tooltip" data-original-title="Delete files" aria-hidden="true"></i>' +
      '</div>' +
      '</div>' +
      '<img src="{%=file.url%}"/>' +
      '</div>' +
      '<div class="info-wrap">' +
      '<div class="title">{%=file.name%}</div>' +
      '</div>' +
      '</div>' +
      '</div>' +
      '{% } %}'
    ),
    forceResize: true,
    previewCanvas: false,
    previewMaxWidth: false,
    previewMaxHeight: false,
    previewThumbnail: false
  }).on('fileuploadprocessalways', function(e, data) {
    var length = data.files.length;

    for (var i = 0; i < length; i++) {
      if (!data.files[i].type.match(/^image\/(gif|jpeg|png|svg\+xml)$/)) {
        data.files[i].filetype = 'other-file';
      } else {
        data.files[i].filetype = 'image';
      }
    }
  }).on('fileuploadadded', function(e) {
    var $this = $(e.target);

    if ($this.find('.file').length > 0) {
      $this.addClass('has-file');
    } else {
      $this.removeClass('has-file');
    }
  }).on('fileuploadfinished', function(e) {
    var $this = $(e.target);

    if ($this.find('.file').length > 0) {
      $this.addClass('has-file');
    } else {
      $this.removeClass('has-file');
    }
  }).on('fileuploaddestroyed', function(e) {
    var $this = $(e.target);

    if ($this.find('.file').length > 0) {
      $this.addClass('has-file');
    } else {
      $this.removeClass('has-file');
    }
  }).on('click', function(e) {
    if ($(e.target).parents('.file').length === 0) $('#inputUpload').trigger('click');
  });

  $(document).bind('dragover', function(e) {
    var dropZone = $('#foodImage'),
      timeout = window.dropZoneTimeout;
    if (!timeout) {
      dropZone.addClass('in');
    } else {
      clearTimeout(timeout);
    }
    var found = false,
      node = e.target;
    do {
      if (node === dropZone[0]) {
        found = true;
        break;
      }
      node = node.parentNode;
    } while (node !== null);
    if (found) {
      dropZone.addClass('hover');
    } else {
      dropZone.removeClass('hover');
    }
    window.dropZoneTimeout = setTimeout(function() {
      window.dropZoneTimeout = null;
      dropZone.removeClass('in hover');
    }, 100);
  });

  $('#inputUpload').on('click', function(e) {
    e.stopPropagation();
  });

  $('#uploadlink').on('click', function(e) {
    e.stopPropagation();
  });
    
    

    // init the wizard
    var defaults = $.components.getDefaults("wizard");
    var options = $.extend(true, {}, defaults, {
      buttonsAppendTo: '.panel-body'
    });

    var wizard = $("#restaurantWizardForm").wizard(options).data('wizard');

    // setup validator
    // http://formvalidation.io/api/#is-valid
    wizard.get("#restaurantStep").setValidator(function() {
      var fv = $("#restaurantForm").data('formValidation');
      fv.validate();

      if (!fv.isValid()) {
        return false;
      }
      
       var post_url ="";
       
       if($("#restaurant_id").length > 0 && $("#restaurant_id").val().length > 0  ){
           
           post_url = "/gadmin/restaurant/edit/"+$("#restaurant_id").val();
           
       }
       else{
           
            post_url = "/gadmin/restaurant/add";
       }
       if(post($("#restaurantForm").serializeArray(),post_url).done(function(response) {
           printStackTrace();
           cosole.log(response);
           return true;
            if(response.code != 200 ){
                 return false;
            }//waits until ajax is completed
            
            $("#restaurantForm").append('<input type="hidden" id="restaurant_id" value="">');
            $("#restaurant_id").val(response.data.restaurant_id);
            return true;
            
        }) === false){
            return false;
        }
//      $.post(post_url,$("#restaurantForm").serializeArray(),function(response){
//          
//
//          getResponse(response);
//          
//      },"json");
      return true;
    });

    wizard.get("#AddMenuItemStep").setValidator(function() {
      var fv = $("#AddMenuItemStepForm").data('formValidation');
      fv.validate();

      if (!fv.isValid()) {
        return false;
      }

      return true;
    });
  })();


  // Example Wizard Form Container
  // -----------------------------
  // http://formvalidation.io/api/#is-valid-container
//  (function() {
//    var defaults = $.components.getDefaults("wizard");
//    var options = $.extend(true, {}, defaults, {
//      onInit: function() {
//        $('#exampleFormContainer').formValidation({
//          framework: 'bootstrap',
//          fields: {
//            username: {
//              validators: {
//                notEmpty: {
//                  message: 'The username is required'
//                }
//              }
//            },
//            password: {
//              validators: {
//                notEmpty: {
//                  message: 'The password is required'
//                }
//              }
//            },
//            number: {
//              validators: {
//                notEmpty: {
//                  message: 'The credit card number is not valid'
//                }
//              }
//            },
//            cvv: {
//              validators: {
//                notEmpty: {
//                  message: 'The CVV number is required'
//                }
//              }
//            }
//          }
//        });
//      },
//      validator: function() {
//        var fv = $('#exampleFormContainer').data('formValidation');
//
//        var $this = $(this);
//
//        // Validate the container
//        fv.validateContainer($this);
//
//        var isValidStep = fv.isValidContainer($this);
//        if (isValidStep === false || isValidStep === null) {
//          return false;
//        }
//
//        return true;
//      },
//      onFinish: function() {
//        // $('#exampleFormContainer').submit();
//      },
//      buttonsAppendTo: '.panel-body'
//    });
//
//    $("#exampleWizardFormContainer").wizard(options);
//  })();

  // Example Wizard Pager
  // --------------------------
  (function() {
    var defaults = $.components.getDefaults("wizard");

    var options = $.extend(true, {}, defaults, {
      step: '.wizard-pane',
      templates: {
        buttons: function() {
          var options = this.options;
          var html = '<div class="btn-group btn-group-sm">' +
            '<a class="btn btn-default btn-outline" href="#' + this.id + '" data-wizard="back" role="button">' + options.buttonLabels.back + '</a>' +
            '<a class="btn btn-success btn-outline pull-right" href="#' + this.id + '" data-wizard="finish" role="button">' + options.buttonLabels.finish + '</a>' +
            '<a class="btn btn-default btn-outline pull-right" href="#' + this.id + '" data-wizard="next" role="button">' + options.buttonLabels.next + '</a>' +
            '</div>';
          return html;
        }
      },
      buttonLabels: {
        next: '<i class="icon wb-chevron-right" aria-hidden="true"></i>',
        back: '<i class="icon wb-chevron-left" aria-hidden="true"></i>',
        finish: '<i class="icon wb-check" aria-hidden="true"></i>'
      },

      buttonsAppendTo: '.panel-actions'
    });

    $("#exampleWizardPager").wizard(options);
  })();

  // Example Wizard Progressbar
  // --------------------------
//  (function() {
//    var defaults = $.components.getDefaults("wizard");
//
//    var options = $.extend(true, {}, defaults, {
//      step: '.wizard-pane',
//      onInit: function() {
//        this.$progressbar = this.$element.find('.progress-bar').addClass('progress-bar-striped');
//      },
//      onBeforeShow: function(step) {
//        step.$element.tab('show');
//      },
//      onFinish: function() {
//        this.$progressbar.removeClass('progress-bar-striped').addClass('progress-bar-success');
//      },
//      onAfterChange: function(prev, step) {
//        var total = this.length();
//        var current = step.index + 1;
//        var percent = (current / total) * 100;
//
//        this.$progressbar.css({
//          width: percent + '%'
//        }).find('.sr-only').text(current + '/' + total);
//      },
//      buttonsAppendTo: '.panel-body'
//    });
//
//    $("#exampleWizardProgressbar").wizard(options);
//  })();

  // Example Wizard Tabs
  // -------------------
//  (function() {
//    var defaults = $.components.getDefaults("wizard");
//    var options = $.extend(true, {}, defaults, {
//      step: '> .nav > li > a',
//      onBeforeShow: function(step) {
//        step.$element.tab('show');
//      },
//      classes: {
//        step: {
//          //done: 'color-done',
//          error: 'color-error'
//        }
//      },
//      onFinish: function() {
//        alert('finish');
//      },
//      buttonsAppendTo: '.tab-content'
//    });
//
//    $("#exampleWizardTabs").wizard(options);
//  })();

  // Example Wizard Accordion
  // ------------------------
//  (function() {
//    var defaults = $.components.getDefaults("wizard");
//    var options = $.extend(true, {}, defaults, {
//      step: '.panel-title[data-toggle="collapse"]',
//      classes: {
//        step: {
//          //done: 'color-done',
//          error: 'color-error'
//        }
//      },
//      templates: {
//        buttons: function() {
//          return '<div class="panel-footer">' + defaults.templates.buttons.call(this) + '</div>';
//        }
//      },
//      onBeforeShow: function(step) {
//        step.$pane.collapse('show');
//      },
//
//      onBeforeHide: function(step) {
//        step.$pane.collapse('hide');
//      },
//
//      onFinish: function() {
//        alert('finish');
//      },
//
//      buttonsAppendTo: '.panel-collapse'
//    });
//
//    $("#exampleWizardAccordion").wizard(options);
//  })();

})(document, window, jQuery);
