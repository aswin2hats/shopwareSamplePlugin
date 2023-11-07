import Plugin from 'src/plugin-system/plugin.class';
export default class hatsLogicPackageScript extends Plugin {

    init() {
        $(".add-more").click(function (e) {
            e.preventDefault();
            let packageFrom = $(".packages-first").html();
            let packageFromHtml = `<div class="row">
                <div class="col-md-11">
                    ${packageFrom}
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger mt-4 remove">-</button>
                </div>
            </div>`;
            $(".additional-packages").append(packageFromHtml);
        })
        $('body').delegate('.remove', 'click', function (e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        })
    }

}