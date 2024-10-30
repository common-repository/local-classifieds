<style type="text/css">
    .control-group {
        margin-bottom: 20px;
    }

    .input-large {
        width:200px;
    }
</style>

<section id="primary" class="site-content" style="width:100%;margin: 0px 20px 20px 0px;">
    <div id="content" role="main">

        <form data-async id="grwaddform" class="form-horizontal" data-action="grwformpostad" method="POST">
            <fieldset>

                <div class="control-group">
                    <label class="control-label" for="ad[title]">Ad Title</label>

                    <div class="controls">
                        <input style="width:50%" id="ad[title]" name="ad[title]" type="text" placeholder="Title" class="input-xlarge" required="">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="ad[description]">Ad Description</label>

                    <div class="controls">
                        <textarea required="" style="width:100%;height:100px;" id="ad[description]" name="ad[description]"></textarea>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="ad[price]">Price</label>

                    <div class="controls">
                        <input id="ad[price]" name="ad[price]" type="text" placeholder="Price" class="input-mini" required="">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="ad[location]">Location</label>

                    <div class="controls">
                        <input id="ad[location]" name="ad[location]" type="text" placeholder="Location" class="input-large" required="">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="ad[website]">Website</label>

                    <div class="controls">
                        <input id="ad[website]" name="ad[website]" type="text" placeholder="Website" class="input-large" required="">
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="ad[adcategory]">Category</label>

                    <div class="controls">
                        <select style="width:40%;" id="ad[adcategory]" name="ad[adcategory]" class="input-small">
                            <option value="other">Other</option>
                            <option value="jobs">Jobs</option>
                            <option value="services">Services</option>
                            <option value="vehicles">Vehicles</option>
                            <option value="electronics">Electronics</option>
                            <option value="pets_animals">Animals</option>
                            <option value="real_estate">Real Estate</option>
                        </select>
                    </div>
                </div>

            </fieldset>

            <button style="width:100px;" type="submit" class="btn">Publish</button>
            <span style="font-size:12px;">*** by pressing [Publish] button you agree with our terms and conditions.</span>

        </form>

        <script>
            jQuery(function ($) {

                 $('form[data-async]').live('submit', function (event) {

                    var $form = $(this);
                    var customdata = {
                        'action': $form.attr('data-action')
                    };

                     $.ajax({
                        url: "<?php echo site_url( '/' )?>wp-admin/admin-ajax.php",
                        data: $form.serialize() + '&' + $.param(customdata),
                        success: function (data, status, xhr) {
                            $("#grwaddform").html("Your ad has been sent to the administrator and will be pubished after review.");
                            $('html, body').animate({scrollTop: $("#grwaddform").offset().top - 100}, 1000);
                        }
                    });

                    event.preventDefault();
                });
            });
        </script>

    </div>
</section>

                            
                            
                            
                            