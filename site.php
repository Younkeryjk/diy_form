<?php
/**
 * wjsw_form模块微站定义
 *
 * @author wangjiasiwei
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Wjsw_formModuleSite extends WeModuleSite {
    /*
     * 表单列表
     */
    public function doWebForm() {
        global $_W, $_GPC;

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;

        $condition = array();
        $keyword = safe_gpc_string($_GPC['name'], '');
        if (!empty($keyword)) {
            $condition['fname LIKE'] = "%{$keyword}%";
        }
        $lists = pdo_getslice('wjsw_form', $condition, array($pindex, $psize), $total,'', 'fid', 'fid desc');
        $pager = pagination($total, $pindex, $psize);
        include $this->template('form_list');
    }

    /*
     * 添加/编辑标签
     */
    public function doWebFormEdit() {
        global $_W, $_GPC;

        $id = safe_gpc_int($_GPC['id']);
        $form = pdo_get('wjsw_form', array('fid' => $id));
        if (checksubmit('submit')) {
            $data = array(
                'fname' => safe_gpc_string($_GPC['fname']),
                'fmsg' => safe_gpc_string($_GPC['fmsg']),
            );
            if (!empty($id)) {
                pdo_update('wjsw_form', $data, array('fid' => $id));
                message('修改成功', $this->createWebUrl('form'));
            } else {
                $data['addtime'] = TIMESTAMP;
                pdo_insert('wjsw_form', $data);
                message('添加成功', $this->createWebUrl('form'));
            }
        }
        include $this->template('form_edit');
    }

    /*
     * 禁用/开启表单
     */
    public function doWebFromDisabled() {
        global $_W, $_GPC;

        $id = safe_gpc_int($_GPC['id']);
        $display = pdo_getcolumn('wjsw_form', array('fid' => $id), 'display');
        if (isset($display)) {
            $display = 1 - $display;
            pdo_update('wjsw_form', array('display' => $display), array('fid' => $id));
            $msg = $display ? '启用成功' : '禁用成功';
        }
        message($msg, $this->createWebUrl('form'));
    }

    /**
     * 删除表单
     */
    public function doWebFromDelete()
    {
        global $_W, $_GPC;

        $id = safe_gpc_int($_GPC['id']);
        pdo_delete('wjsw_form_data', array('fid' => $id));
        pdo_delete('wjsw_form_type', array('fid' => $id));
        pdo_delete('wjsw_form', array('fid' => $id));
        message("删除成功", $this->createWebUrl('form'));
    }

    /*
     * 表单选项列表
     */
    public function doWebFormOptionList() {
        global $_W, $_GPC;

        $fid = safe_gpc_int($_GPC['fid']);
        $formName = pdo_getcolumn('wjsw_form', array('fid' => $fid), 'fname');
        $lists = pdo_getall('wjsw_form_type', array('fid' => $fid), array('id', 'type', 'title', 'orderid', 'isrequired'), '', 'orderid asc');
        include $this->template('form_option_list');
    }

    /*
     * 编辑表单选项
     */
    public function doWebFormOptionEdit() {
        global $_W, $_GPC;

        $id = safe_gpc_int($_GPC['id']);
        $fid = safe_gpc_int($_GPC['fid']);
        $formName = safe_gpc_string($_GPC['formName']);
        if (checksubmit('submit')) {
            $data = array(
                'fid' => $fid,
                'orderid' => safe_gpc_int($_GPC['orderid']),
                'type' => safe_gpc_string($_GPC['type']),
                'title' => safe_gpc_string($_GPC['title']),
                'msg' => safe_gpc_string($_GPC['msg']),
                'options' => safe_gpc_string($_GPC['options']),
                'defaultvalue' => safe_gpc_string($_GPC['defaultvalue']),
                'isverification' => safe_gpc_string($_GPC['isverification']),
                'isrequired' => safe_gpc_int($_GPC['isrequired']),
            );
            if (!empty($id)) {
                pdo_update('wjsw_form_type', $data, array('id' => $id));
                message('修改成功', $this->createWebUrl('FormOptionList', array('fid' => $fid, 'formName' => $formName)));
            } else {
                pdo_insert('wjsw_form_type', $data);
                message('添加成功', $this->createWebUrl('FormOptionList', array('fid' => $fid, 'formName' => $formName)));
            }
        }
        $option = pdo_get('wjsw_form_type', array('id' => $id));
        $diplay = 'display:none';
        if ($option) {
            if (in_array($option['type'], array('radio', 'checkbox', 'select'))) {
                $diplay = '';
            }
        }
        include $this->template('form_option_edit');
    }

    /*
     * 删除表单选项
     */
    public function doWebFromOptionDelete() {
        global $_W, $_GPC;

        $id = safe_gpc_int($_GPC['id']);
        $fid = safe_gpc_int($_GPC['fid']);
        $formName = safe_gpc_string($_GPC['formName']);
        pdo_delete('wjsw_form_type', array('id' => $id));
        message("删除成功", $this->createWebUrl('FormOptionList', array('fid' => $fid, 'formName' => $formName)));
    }

    /*
     * 表单用户数据列表
     */
    public function doWebFormDataList() {
        global $_W, $_GPC;
        $fid = safe_gpc_int($_GPC['fid']);
        $formName = safe_gpc_string($_GPC['formName']);

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $lists = pdo_getslice('wjsw_form_data', array('fid' => $fid), array($pindex, $psize), $total,array(), '', 'addtime desc');
        $pager = pagination($total, $pindex, $psize);
        foreach($lists AS $key => $value) {
            $c = unserialize($value['content']);
            if(count($c['title'])) {
                foreach($c['title'] AS $k => $title) {
                    $content = $c['content'][$k];
                    if(is_array($content)) {
                        $content = implode(',', $content);
                    }
                    $content = str_replace("\r\n","<br>",$content);
                    $lists[$key][$title] = $content;
                }
            }
            $lists[$key]['提交日期'] = date("Y-m-d", $value['addtime']);
            unset($lists[$key]['fid'], $lists[$key]['addtime'], $lists[$key]['content']);
        }
        include $this->template('form_data_list');
    }

    /*
     * 删除用户数据
     */
    public function doWebFormDataDelete() {
        global $_W, $_GPC;
        $fid = safe_gpc_int($_GPC['fid']);
        $formName = safe_gpc_string($_GPC['formName']);
        $ids = safe_gpc_string($_GPC['ids']);
        pdo_delete('wjsw_form_data', array('id' => $ids));
        message("删除成功", $this->createWebUrl('FormDataList', array('fid' => $fid, 'formName' => $formName)));
    }

    /**
     * 应用前台入口文件
     */
    public function doMobileIndex() {
        global $_W,$_GPC;
        $lists = pdo_getall('wjsw_form', array('display' => 1));
        include $this->template('index');
    }

    /**
     * 表单页面
     */
    public function doMobileForm() {
        global $_W,$_GPC;
        $fid = safe_gpc_int($_GPC['fid']);
        $form = pdo_get('wjsw_form', array('fid' => $fid));
        //判断表单是否已禁用
        if (!$form['display']) {
            itoast('表单已关闭');
        }
        if (checksubmit('submit')) {
            $data = array(
                'fid' => $fid,
                'addtime' => TIMESTAMP,
                'content' => array(
                    'title' => array(),
                    'content' => array(),
                ),
            );
            //用户提交后显示的数据
            $dataList = array();
            //过滤掉隐藏域的字段
            $hiddenTitles = pdo_getall('wjsw_form_type', array('fid' => $fid, 'type' => 'hidden'), 'title');
            $hiddenTitleArr = array('token','submit');
            if ($hiddenTitles) {
                foreach ($hiddenTitles as $hiddenTitle) {
                    $hiddenTitleArr[] = $hiddenTitle['title'];
                }
            }

            foreach ($_POST as $title => $content) {
                $title = safe_gpc_string($title);
                if (in_array($title, $hiddenTitleArr)) {
                    continue;
                }
                $content = safe_gpc_string($content);
                $dataList[$title] = is_array($content) ? implode(',', $content) : $content;
                $data['content']['title'][] = $title;
                $data['content']['content'][] = $content;
            }
            $data['content'] = serialize($data['content']);
            pdo_insert('wjsw_form_data', $data);
            $formName = pdo_getcolumn('wjsw_form', array('fid' => $fid), 'fname');
            include $this->template('view_data');
            exit();
        }
        $formOptionList = pdo_getall('wjsw_form_type', array('fid' => $fid), array(), '', 'orderid asc');
        foreach ($formOptionList as $key => $value) {
            if ($value['options']) {
                $formOptionList[$key]['options'] = explode(PHP_EOL, $value['options']);
            }
        }
        include $this->template('form');
    }
}