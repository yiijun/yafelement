Vue.prototype.$ELEMENT = { size: 'small', zIndex: 3000 };
function btnLogout() {
    $.post('/login/logout', {
        data: {id:1}
    }).done(function(response) {
        if(200 == response.code){
            vm.$message.success(response.msg);
            setTimeout(function () {
                location.href = '/login';
            },1000);
        }else{
            vm.$message.error(response.msg);
        }
    })
}
function show(type){
    if(type == 1){
        $(".el-aside").css('width',"auto");
        $(".btn-show").css('padding-left','20px');
        $(".logo").hide();
        vm.isCollapse = true;
    }else{
        $(".btn-show").css('padding-left','39px');
        vm.isCollapse = false;
        $(".logo").show();
    }
}
function btnAdminInfo(){
    vm.dialogVisible = true;
}

if (!String.prototype.trim) {
    String.prototype.trim = function () {
        return this.replace(/(^[\s\n\t]+|[\s\n\t]+$)/g, "");
    }
}