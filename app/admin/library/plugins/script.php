<?php
/**
 * User: tangyijun
 * Date: 2020-01-03
 * Time: 10:15
 */
use \libs\instance;
abstract class Plugins_Script extends Instance
{
    public function submitForm($controller,$fields,$reload)
    {
$html = <<<submitForm
submitForm:function(formName) {
                this.\$refs[formName].validate((valid) => {
                    if (valid) {
                        $.post('/<?php echo $controller."/post"?>', vm.form).done(function(response) {
                            if(200 == response.code){
                                vm.form = <?php echo Building::getInstance()->renderClearForm($fields)?>;
                                vm.\$message.success(response.msg);
                                let is_reload = <?php echo $reload?:0?>;
                                if(is_reload){
                                    window.location.reload();
                                }else{
                                    vm.init();
                                }
                                vm.dialog = false
                            }else{
                                vm.\$message.error(response.msg);
                            }
                        })
                    } else {
                        return false;
                    }
                });
            },
submitForm;
        return $html;
    }

    public function deleteForm()
    {

    }

    public function saveForm()
    {

    }





}