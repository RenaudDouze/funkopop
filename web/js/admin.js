/**
 * Creer le champ text pour la saisie de tags
 *
 * @param  {string}       target
 * @param  {array|object} options
 * @param  {array|object} autocompleteSource
 *
 * @return {object}       
 */
function createTagsInput(target, options, autocompleteSource)
{
    var tagsInputId = target.replace('tags-container-', '');
    var tagsInput = $('#' + tagsInputId);
    tagsInput.hide();

    var params = _.extend({
        allowDuplicates: false,
        placeholder: '',
        onTagAdd: function(event, tag) {
            var content = tagsInput.val();

            content += ';' + tag;
            content = content.replace(';;', ';');
            content.trim();

            if (';' === content.charAt(0)) {
                content = content.slice(1, -1);
            }

            tagsInput.val(content);
        },
        onTagRemove: function(event, tag) {
            var content = tagsInput.val();

            content.replace(';' + tag, '');
            content.trim();

            if (';' === content.charAt(0)) {
                content = content.slice(1, -1);
            }

            tagsInput.val(content);
        }
    }, options);

    var taggle = new Taggle(target, params);

    var container = taggle.getContainer();
    var input = taggle.getInput();

    autocompleteSource = (('undefined' === typeof autocompleteSource) ? 
      function(request, response) {
        $.ajax({
          url: Routing.generate('app_admin_tagautocomplete'),
          data: {
            input: request.term
          },
          success: function(data) {
            response(data);
          }
        });
      } : autocompleteSource
    );

    //*
    $(input).autocomplete({
        appendTo: container,
        position: { at: "left bottom", of: container },
        source: autocompleteSource,
        minLength: 1,
        select: function(event, data) {
          event.preventDefault();

          if (1 === event.which) {
              taggle.add(data.item.value);
          }
        },
        open: function() {
          $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        },
        close: function() {
          $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
        }
    });
    /**/
}