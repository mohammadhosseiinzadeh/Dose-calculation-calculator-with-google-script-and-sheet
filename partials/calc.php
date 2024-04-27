<div class="form-v4">
    <?php
            if ((isset($_GET['ocl-form']) && $_GET['ocl-form'] == "dose") || (isset($_GET['ocl-form']) && $_GET['ocl-form'] == "roshd")) {
                ?>
                <!--
                <div class="return-back">
                   <a href="https://cinnatropin.com/%D9%85%D8%AD%D8%A7%D8%B3%D8%A8%D9%87-%DA%AF%D8%B1/">بازگشت به صفحه اصلی محاسبه‌گر</a>
                </div>
                -->
                <?php
            }
        ?>
    <div class="form-v4-page-content">
        <div class="form-v4-content">
            <?php
            if (isset($_GET['ocl-form']) && $_GET['ocl-form'] == "dose") {
                include OCL_PART . '/doz.php';
            } elseif (isset($_GET['ocl-form']) && $_GET['ocl-form'] == "roshd") {
                include OCL_PART . '/roshd.php';
            } else {
                include OCL_PART . '/select-calc.php';
            }
            ?>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var elements = document.getElementsByTagName("INPUT");
    for (var i = 0; i < elements.length; i++) {
        elements[i].oninvalid = function(e) {
            e.target.setCustomValidity("");
            if (!e.target.validity.valid) {
                e.target.setCustomValidity("تکمیل این بخش ضروری است.");
            }
        };
        elements[i].oninput = function(e) {
            e.target.setCustomValidity("");
        };
    }
})
</script>
