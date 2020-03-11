<div class="mf-dialog__wrapper" style="z-index:2028;" id="menue-set">
    <div class="mf-dialog" style="margin-top: 15vh;width:800px;">
      <div class="mf-dialog__header">
        <span class="mf-dialog__title"><?php echo \App\Components\Tools::sLang('Select reply content', '选择回复内容');?></span>
        <button type="button" aria-label="Close" class="mf-dialog__headerbtn" onclick="$('.v-modal').hide();$('#menue-set').hide();">
          <i class="mf-dialog__close mf-icon mf-icon-close"></i>
        </button>
      </div>
      <div class="mf-dialog__body">
        <div class="mf-tabs mf-tabs--left">
          <div class="mf-tabs__header is-left">
            <div class="mf-tabs__nav-wrap is-left">
              <div class="mf-tabs__nav-scroll">
                <div role="tablist" class="mf-tabs__nav is-left" style="transform: translateY(0px);">
                    <div class="mf-tabs__active-bar is-left" style="transform: translateY(0px); height: 40px;"></div>
                    <div id="tab-0" aria-controls="pane-0" role="tab" tabindex="0" class="mf-tabs__item is-left is-active" aria-selected="true" onclick="materialType(this)" material-type="click"><?php echo \App\Components\Tools::sLang('Text ', '文字');?></div>
                    <div id="tab-1" aria-controls="pane-1" role="tab" tabindex="1" class="mf-tabs__item is-left" onclick="materialType(this)" material-type="media_id"><?php echo \App\Components\Tools::sLang('Image ', '图片');?></div>
                    <div id="tab-2" aria-controls="pane-2" role="tab" tabindex="2" class="mf-tabs__item is-left" onclick="materialType(this)" material-type="media_id"><?php echo \App\Components\Tools::sLang('News ', '图文消息');?></div>
                    <div id="tab-3" aria-controls="pane-3" role="tab" tabindex="3" class="mf-tabs__item is-left" onclick="materialType(this)" material-type="view"><?php echo \App\Components\Tools::sLang('Link ', '跳转链接');?></div>
                    <div id="tab-4" aria-controls="pane-4" role="tab" tabindex="4" class="mf-tabs__item is-left" onclick="materialType(this)" material-type="robot"><?php echo \App\Components\Tools::sLang('Enter ROBOT ', '进入ROBOT');?></div>
                    <div id="tab-5" aria-controls="pane-5" role="tab" tabindex="5" class="mf-tabs__item is-left" onclick="materialType(this)" material-type="miniprogram"><?php echo \App\Components\Tools::sLang('Miniprogram ', '小程序');?></div>
                    <div id="tab-6" aria-controls="pane-6" role="tab" tabindex="6" class="mf-tabs__item is-left" onclick="materialType(this)" material-type="media_id"><?php echo \App\Components\Tools::sLang('Video ', '视频');?></div>
                  <!--<div id="tab-3" aria-controls="pane-3" role="tab" tabindex="-1" class="mf-tabs__item is-left">自定义key</div>-->
                </div>
              </div>
            </div>
          </div>
            <div class="mf-tabs__content" style="min-height:400px;max-height:400px;overflow-y:auto;margin-left: 5%;">
            <div role="tabpanel" id="pane-0" aria-labelledby="tab-0" class="mf-tab-pane">
                <?php if($textList['total'] > 0){ ?>
                <ul class="asset-list">
                    <?php foreach ($textList['data'] as $tval){ ?>
                    <li class="item" val="<?php echo $tval['crc_token'];?>">
                        <div class="content">
                            <p><?php echo $tval['content'];?></p>
                        </div>
                    </li>
                    <?php }?>
                </ul>
                <?php }?>
                <?php if($textList['total'] > 10){?>
                <button type="button" class="btn" style="margin-left:230px;" onclick="appendContent(this)" pid="pane-0" page="2" ptype="1"><?php echo \App\Components\Tools::sLang('Load more', '加载更多');?></button>
                <?php }?>
            </div>
            <div role="tabpanel" id="pane-1" aria-labelledby="tab-1" class="mf-tab-pane" aria-hidden="true" style="display: none;">
                <?php if($imageList['total'] > 0){ ?>
                <ul class="asset-list">
                    <?php foreach ($imageList['data'] as $ival){ ?>
                    <li class="item" val="<?php echo $ival['media_id'];?>">
                      <div class="content">
                        <div class="avatar" style="background-image: url(<?php echo env('IMAGE_DOMIAN').$ival['url'];?>)"></div>
                      </div>
                    </li>
                    <?php }?>
                </ul>
                <?php }?>
                <?php if($imageList['total'] > 10){?>
                <button type="button" class="btn" style="margin-left:230px;" onclick="appendContent(this)" pid="pane-1" page="2" ptype="2"><?php echo \App\Components\Tools::sLang('Load more', '加载更多');?></button>
                <?php }?>
            </div>
            <div role="tabpanel" id="pane-2" aria-labelledby="tab-2" class="mf-tab-pane" aria-hidden="true" style="display: none;">
              <?php if($newsList['total'] > 0){ ?>
              <ul class="asset-list">
                    <?php foreach ($newsList['data'] as $nval){ ?>
                <li class="item" val="<?php echo $nval['media_id'];?>">
                  <div class="content">
                    <div class="avatar" style="background-image: url(<?php echo env('IMAGE_DOMIAN').$nval['url'][0];?>)">
                      <p class="title"><?php echo $nval['title'][0];?></p>
                    </div>
                  </div>
                    <?php if(count($nval['url']) > 1){ for($i = 1;$i<count($nval['url']);$i++){?>
                  <div class="line">
                    <p class="txt"><?php echo $nval['title'][$i];?></p>
                    <div class="avatar" style="background-image: url(<?php echo env('IMAGE_DOMIAN').$nval['url'][$i];?>)"></div>
                  </div>
                    <?php }}?>
                </li>
                <?php }?>
              </ul>
                <?php }?>
                <?php if($newsList['total'] > 10){?>
                <button type="button" class="btn" style="margin-left:230px;" onclick="appendContent(this)" pid="pane-2" page="2" ptype="3"><?php echo \App\Components\Tools::sLang('Load more', '加载更多');?></button>
                <?php }?>
            </div>
            <div role="tabpanel" id="pane-3" aria-labelledby="tab-3" class="mf-tab-pane" aria-hidden="true" style="display: none;">
              <p>*<?php echo \App\Components\Tools::sLang('Please fill in the link address of clicking menu.', '请填写点击菜单跳转的链接地址');?></p>
              <div class="mf-input mf-input-group mf-input-group--prepend">
                <!--<div class="mf-input-group__prepend">http://</div>-->
                <input type="text" autocomplete="off" placeholder="<?php echo \App\Components\Tools::sLang('Please input contents', '请输入内容');?>" class="mf-input__inner" placeholder="http://" id="material_url">
              </div>
            </div>
            <div role="tabpanel" id="pane-4" aria-labelledby="tab-4" class="mf-tab-pane" aria-hidden="true" style="display: none;">
              <ul class="asset-list">
                <li class="item active" val="PuppetRobot">
                  <div class="content">
                    <p><?php echo \App\Components\Tools::sLang('ROBOT custom auto reply', 'ROBOT自定义自动回复');?></p>
                  </div>
                </li>
              </ul>
            </div>
            <div role="tabpanel" id="pane-5" aria-labelledby="tab-5" class="mf-tab-pane" aria-hidden="true" style="display: none;">
              <p><?php echo \App\Components\Tools::sLang('Subscribers clicking the submenu will skip to the following miniprogram', '订阅者点击该子菜单会跳到以下小程序');?></p>
              <div class="mf-input mf-input-group mf-input-group--prepend">
                <p><?php echo \App\Components\Tools::sLang('miniprogram developer ID(APPID)', '小程序开发者ID(APPID)');?></p>
                <input type="text" autocomplete="off" placeholder="<?php echo \App\Components\Tools::sLang('miniprogram developer ID(APPID)', '小程序开发者ID(APPID)');?>" class="mf-input__inner" id="miniprogram_appid">
                <p><?php echo \App\Components\Tools::sLang('miniprogram path', '小程序路径');?></p>
                <input type="text" autocomplete="off" placeholder="<?php echo \App\Components\Tools::sLang('miniprogram path', '小程序路径');?>" class="mf-input__inner" id="miniprogram_pagepath">
                <p><?php echo \App\Components\Tools::sLang('Standby web page', '备用网页');?></p>
                <input type="text" autocomplete="off" placeholder="<?php echo \App\Components\Tools::sLang('Standby web page', '备用网页');?>" class="mf-input__inner" id="miniprogram_url">
                <p><?php echo \App\Components\Tools::sLang('The old WeChat client could not support the applet. When users click the menu, they will open the backup web page.', '旧版微信客户端无法支持小程序，用户点击菜单时将会打开备用网页。');?></p>
              </div>
            </div>
            <div role="tabpanel" id="pane-6" aria-labelledby="tab-6" class="mf-tab-pane" aria-hidden="true" style="display: none;">
              <?php if($videoList['total'] > 0){ ?>
              <ul class="asset-list">
                <?php foreach ($videoList['data'] as $vval){ ?>
                <li class="item" val="<?php echo $vval['media_id'];?>">
                  <div class="content">
                    <p><?php echo $vval['title'];?></p>
                  </div>
                </li>
                <?php }?>
              </ul>
              <?php }?>
              <?php if($videoList['total'] > 10){?>
              <button type="button" class="btn" style="margin-left:230px;" onclick="appendContent(this)" pid="pane-6" page="2" ptype="6"><?php echo \App\Components\Tools::sLang('Load more', '加载更多');?></button>
              <?php }?>
            </div>
          </div>
        </div>
      </div>
      <div class="mf-dialog__footer">
        <div class="dialog-footer">
            <button type="button" class="mf-button mf-button--default" onclick="$('.v-modal').hide();$('#menue-set').hide();">
                <span><?php echo \App\Components\Tools::sLang('Cancel', '取 消');?></span>
            </button>
            <button type="button" class="mf-button mf-button--primary" onclick="saveMaterialData()">
                <span><?php echo \App\Components\Tools::sLang('Save', '确 定');?></span>
            </button>
        </div>
      </div>
    </div>
</div>
<div class="v-modal" tabindex="0" style="z-index: 2027;"></div>
<script>
var image_domain = '{{ env('IMAGE_DOMIAN') }}';
function materialType(obj){
  $('.mf-tabs__item').each(function(){
    $(this).removeClass('is-active');
    $(this).removeAttr('aria-selected');
  });
  $(obj).attr('aria-selected','true');
  $(obj).addClass('is-active');
  var index = $(obj).attr('tabindex');
  var he = 'translateY('+index*40+'px)';
  $('.mf-tabs__active-bar').css('transform',he);
  for(var i=0;i<=6;i++){
    $('#pane-'+i).hide();
  }
  $('#pane-'+index).show();
  if($(obj).attr('material-type') == 'robot'){
    $('#material_type').val('click');
    $('#material_content').val('PuppetRobot');
  }else{
    $('#material_type').val($(obj).attr('material-type'));
  }
}
$(function(){
  $(".asset-list").on("click","li",function(event) {
    var $this = $(this);
    if($this.hasClass("active")){
      $this.removeClass("active");
      giveVal('');
    }else{
      $(".asset-list li").removeClass("active");
      $this.addClass("active");
      giveVal($this.attr('val'));
    }
  });
})
function giveVal(val){
  var material_type = $('#material_type').val();
  if(material_type == 'click'){
    $('#material_content').val(val);
    $('#material_media_id').val('');
    $('#material_url').val('');
  }else if(material_type == 'view'){
    $('#material_content').val('');
    $('#material_media_id').val('');
    $('#material_url').val(val);
  }else if(material_type == 'media_id'){
    $('#material_content').val('');
    $('#material_media_id').val(val);
    $('#material_url').val('');
  }else if(material_type == 'robot'){
    $('#material_content').val('PuppetRobot');
    $('#material_media_id').val('');
    $('#material_url').val('');
  }else{
    $('#material_content').val('');
    $('#material_media_id').val('');
    $('#material_url').val('');
  }
  return true;
}
//点击加载更多
function appendContent(obj){
  var p_type = $(obj).attr('ptype');
  var p_page = $(obj).attr('page');
  var p_pid = $(obj).attr('pid');
  var url = "{{URL('admin/wechat/material/list')}}";
  var data = {type:p_type,page:p_page};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    alert(result.msg);
    return false;
  }else{
    var material_html = '';
    switch(parseInt(p_type)){
      case 1:
        for(var i =0;i<result.data.data.length;i++){
          material_html += '<li class="item" val="'+result.data.data[i].crc_token+'"><div class="content"><p>'+result.data.data[i].content+'</p></div></li>';
        }
        break;
      case 2:
        for(var i =0;i<result.data.data.length;i++){
          material_html += '<li class="item" val="'+result.data.data[i].media_id+'"><div class="content"><div class="avatar" style="background-image: url('+image_domain+result.data.data[i].url+')"></div></div></li>';
        }
        break;
      case 3:
        for(var i =0;i<result.data.data.length;i++){
          var this_material_html= '<li class="item" val="'+result.data.data[i].media_id+'"><div class="content"><div class="avatar" style="background-image: url('+image_domain+result.data.data[i].url[0]+')"><p class="title">'+result.data.data[i].title[0]+'</p></div></div>';
          if(result.data.data[i].url.length > 1){
            for(var j = 1;j<result.data.data[i].url.length;j++){
              this_material_html += '<div class="line"><p class="txt">'+result.data.data[i].title[j]+'</p><div class="avatar" style="background-image: url('+image_domain+result.data.data[i].url[j]+')"></div></div>';
            }
          }
          this_material_html += '</li>';
          material_html += this_material_html;
        }
        break;
      case 4:
        material_html = '<li class="item active" val="PuppetRobot"><div class="content"><p>'+ROBOT自定义自动回复+'</p></div></li>';
        break;
      case 6:
        for(var i =0;i<result.data.data.length;i++){
          material_html += '<li class="item" val="'+result.data.data[i].media_id+'"><div class="content"><p>'+result.data.data[i].title+'</p></div></li>';
        }
        break;
    }
    $('#'+p_pid).children('ul').append(material_html);
    if(result.data.page >= (parseInt(p_page) + 1)){
      $(obj).attr('page',(parseInt(p_page) + 1));
    }else{
      $(obj).hide();
    }
  }
}
</script> 