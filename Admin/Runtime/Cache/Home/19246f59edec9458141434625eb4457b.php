<?php if (!defined('THINK_PATH')) exit();?><div class="page-container">
                            <p>您可以尝试文件拖拽，使用QQ截屏工具，然后激活窗口后粘贴，或者点击添加图片按钮，来体验此demo.</p>
                            <div id="uploader" class="wu-example">
                                <div class="queueList">
                                    <div id="dndArea" class="placeholder">
                                        <div id="filePicker"></div>
                                        <p>或将照片拖到这里，单次最多可选300张</p>
                                    </div>
                                </div>
                                <div class="statusBar" style="display:none;">
                                    <div class="progress">
                                        <span class="text">0%</span>
                                        <span class="percentage"></span>
                                    </div>
                                    <div class="info"></div>
                                    <div class="btns">
                                        <div id="filePicker2"></div>
                                        <div class="uploadBtn">开始上传</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                            <script type="text/javascript">
	        // 添加全局站点信息
	        var BASE_URL = '/Public/static/js/plugins/webuploader';
	        var SERVER_URL = "<?php echo U('Public/ajax_upload');?>";
	    </script>
	    <script src="/Public/static/js/plugins/webuploader/webuploader.min.js"></script>
	    <script src="/Public/static/js/demo/webuploader-demo.js"></script>