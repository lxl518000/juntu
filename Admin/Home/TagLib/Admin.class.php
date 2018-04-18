<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Home\TagLib;

use Think\Template\TagLib;

/**
 * base标签库驱动
 */
class Admin extends TagLib
{
    // 标签定义
    protected $tags = array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'editor' => array('attr' => 'id,name,content,style,width,height,type,itemno,class', 'close' => 1),
        'plone' => array('attr' => 'button,multi,exts,maxsize,folder,field,filename,callback,url,progress', 'close' => 1),
    );

   
    /**
     * PL单文件上传
     * 格式： <base:upload >{$vo.remark}</base:upload>
     * @access public
     * @param array $tag 标签属性
     * @return string|void
     */
    public function _plone($tag)
    {
        $button = !empty($tag['button']) ? $tag['button'] : 'pickfiles';
        $exts = !empty($tag['exts']) ? $tag['exts'] : 'jpg,gif,png';
        $folder = !empty($tag['folder']) ? $tag['folder'] : '';
        $field = !empty($tag['field']) ? $tag['field'] : '';
        $filename = !empty($tag['filename']) ? $tag['filename'] : 'filename';
        $url = !empty($tag['url']) ? $tag['url'] : '/api.php?s=Upload/plsave';
        $maxSize = !empty($tag['maxsize']) ? $tag['maxsize'] : '2.5gb';
        // runtimes: "html5,flash,silverlight,html4",
        if (!empty($tag['folder'])) {
            $url .= '/folder/' . $folder;
        }
        $parseStr = '<script type="text/javascript" src="/Public/Vendor/plupload/js/plupload.full.js"></script>
        		<link rel="stylesheet" href="/Public/Vendor/plupload/css/progress.css">
        <script type="text/javascript" src="/Public/Vendor/plupload/js/i18n/zh_cn.js"></script>
                <script type="text/javascript">
                    var uploader_' . $field . ' = new plupload.Uploader({
                        runtimes: "html5,html4,flash",
                        browse_button: "' . $button . '",
                        //container: "container",
                        max_file_size : "'.$maxSize.'",
                        chunk_size : "2mb",
                        unique_names : true,  // 上传的文件名是否唯一
                        multi_selection: "' . $tag['multi'] . '" == "1" ? true : false,
                        multipart : true,
                        multipart_params:{},
                        url : "' . $url . '",
                        /*resize : {width : 320, height : 240, quality : 90},*/// 是否生成缩略图（仅对图片文件有效）
                        flash_swf_url : "/Public/Vendor/plupload/js/plupload.flash.swf",
                        silverlight_xap_url : "/Public/Vendor/plupload/js/plupload.silverlight.xap",

                        filters:[
                                {title: "上传文件类型", extensions: "' . $exts . '"}
                        ],
                        preinit : {
                            UploadFile: function(up, file) {
                                up.settings.multipart_params = {sourceName: file.name};
                            }
                        },

                        init: {
                            FilesAdded: function (up, files) {
                                plupload.each(files, function (file) {                              	
                                    $("#container").html("<div class=\"progress\"><div class=\"bar\" style=\"width: 1%;height:36px;line-height:36px;\"></div></div>");
                                    uploader_' . $field . '.start();
                                });
                            },

                            UploadProgress: function (up, file) { ';
		     	if ($tag['progress'] != '') {
		     		
		            $parseStr .= '$(".progress > .bar").css("width", file.percent+"%").html(file.percent);';
		            if($tag['needftp'] == 1){
		            	$parseStr .= 'if(file.percent==100){
    											$(".progress > .bar").html("等待FTP上传");		
    										}';
		            }
		        }
		        
		        $parseStr .= '
                                
                              
                            },

                            Error: function (up, err) {
                                alert("上传失败:"+err.message);
                            },
                            FileUploaded: function (up, file, info) {
                              res = $.parseJSON(info.response);
                                $("#' . $filename . '").val(file.name);
                               	$(".progress > .bar").html("文件上传成功");
                               
                               ';
        if ($tag['callback'] != '') {
            $parseStr .= $tag['callback'] . '(res)';
        }
        $parseStr .= '}
                            }
                        });
                        uploader_' . $field . '.init();
    			</script>
						    
    			';
        return $parseStr;
    }
}