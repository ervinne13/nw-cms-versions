
let CMSVersion = (function () {

    "use strict";

    let model;
    let modelId;
    let actionsSelector;
    let baseUrl;

    function init() {

        let $versionIdField = $('.version-id-field');

        baseUrl = $versionIdField.data('base-url');
        model = $versionIdField.data('model');
        modelId = $versionIdField.data('model-id');
        actionsSelector = $versionIdField.data('actions-selector');

        initEvents($versionIdField);

    }

    function initEvents($versionIdField) {

        $versionIdField.change(function () {
            let versionId = $(this).val();

            if (actionsSelector) {
                $(actionsSelector).attr('disabled', true);
            }

            loadVersionData(versionId)
                    .then(versionData => {
                        $(actionsSelector).attr('disabled', false);
                        processVersionData(versionData);
                    })
                    .fail(e => {
                        $(actionsSelector).attr('disabled', false);
                        //  TODO
                        console.error(e);
                    });
        });
    }

    function loadVersionData(versionId) {
        let url = `${baseUrl}/model/${model}/version/${versionId}/data/${modelId}`;
        return $.get(url);
    }

    function processVersionData(version) {
        if (typeof version === 'string') {
            version = JSON.parse(version);
        }

        console.log(version);

        version.fields.forEach(field => {
            let value = version.data ? version.data[field.is_published_field] : null;
            updateFieldData(field.is_published_field, value);
        });

    }

    function updateFieldData(fieldName, newValue) {
        $(`[name=${fieldName}]`).val(newValue);
        console.log(`[name=${fieldName}]`);

        //  in case of icheck
        if ($(`[name=${fieldName}]`).iCheck && newValue) {
            $(`[name=${fieldName}]`).iCheck('check');
        } else if ($(`[name=${fieldName}]`).iCheck) {
            $(`[name=${fieldName}]`).iCheck('uncheck');
        }
    }

    return {init};

})();
