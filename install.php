<?php
$sql =
    "
CREATE TABLE IF NOT EXISTS " . tablename('wjsw_form') . " (
  `fid` int(11) NOT NULL auto_increment COMMENT '表单ID',
  `fname` char(100) NOT NULL COMMENT '表单名称',
  `fmsg` mediumtext NOT NULL COMMENT '表单说明',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  `display` int(1) NOT NULL default '1',
  PRIMARY KEY  (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO " . tablename('wjsw_form') . " (`fid`, `fname`, `fmsg`, `addtime`, `display`) VALUES
(1, '演示表单', '这个是演示表单，供用户参考！', 1209817185, 1),
(2, '客户调查', '调查一些客户信息', 1576717736, 1);

CREATE TABLE IF NOT EXISTS " . tablename('wjsw_form_type') . " (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(20) NOT NULL COMMENT '所属表单ID',
  `orderid` int(10) NOT NULL COMMENT '排序',
  `type` varchar(20) NOT NULL COMMENT '类型',
  `title` varchar(40) NOT NULL COMMENT '标题',
  `msg` varchar(255) NOT NULL COMMENT '说明',
  `options` mediumtext NOT NULL COMMENT '选项',
  `defaultvalue` mediumtext NOT NULL COMMENT '默认值',
  `isverification` char(10) NOT NULL COMMENT '表单内容项限制',
  `isrequired` tinyint(4) NOT NULL COMMENT '是否是必填',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO " . tablename('wjsw_form_type') . " (`id`, `fid`, `orderid`, `type`, `title`, `msg`, `options`, `defaultvalue`, `isverification`, `isrequired`) VALUES
(6, 1, 255, 'textarea', '内容', '', '', '', '',1),
(5, 1, 15, 'radio', '婚姻状况', '', '已婚\r\n未婚', '', '',0),
(4, 1, 14, 'text', '地址', '本字段不做限制', '', '', '',0),
(3, 1, 15, 'checkbox', '爱好', '请选择您的爱好', '游戏\r\n音乐\r\n看书\r\n旅游', '', '',0),
(2, 1, 2, 'text', 'E_mail', '本字段必须为Email格式', '', '', 'email',0),
(1, 1, 1, 'text', '姓名', '本字段不能为空', '', '', '',1),
(7, 1, 7, 'text', '电话', '本字段只能输入电话号码', '', '', 'phone',0),
(8, 1, 8, 'text', '手机', '本字段只能输入手机号码', '', '', 'mobile',0),
(9, 1, 9, 'text', '邮政编码', '本字段只能输入邮政编码格式', '', '', 'post',0),
(10, 1, 10, 'text', '主页', '本字段只能输入网址格式', '', '', 'url',0),
(11, 1, 11, 'text', 'QQ', '本字段只能输入QQ号码格式', '', '', 'qq',0),
(12, 1, 12, 'text', '英文名', '本字段只能输入英文字母', '', '', 'english',0),
('13', '2', '1', 'text', '姓名', '客户姓名', '', '', '', '1'),
('14', '2', '2', 'text', '邮箱', '客户', '', '', 'email', '1'),
('15', '2', '3', 'text', '联系方式', '客户的联系方式', '', '', 'mobile', '1'),
('16', '2', '8', 'text', '邮政编码', '用户所在地的邮政编码', '', '', 'post', '0'),
('17', '2', '9', 'text', '主页', '客户的网址主页', '', '', 'url', '0'),
('18', '2', '10', 'text', 'QQ', '客户的QQ', '', '', 'qq', '0'),
('19', '2', '4', 'text', '住址', '客户的住址', '', '', '', '0'),
('20', '2', '5', 'radio', '婚姻状况', '客户的婚姻状况', '已婚\r\n未婚', '', '', '0'),
('21', '2', '6', 'checkbox', '爱好', '客户的爱好', '游戏\r\n音乐\r\n看书\r\n旅游', '', '', '0'),
('22', '2', '7', 'select', '职业', '客户的职业', '行政主管\r\n企业主管\r\n经理人\r\n土木营造监工\r\n天文学家\r\n电脑程式设计人员\r\n系统分析师\r\n其他', '', '', '0'),
('23', '2', '11', 'password', '通行令', '客户在本俱乐部的通行令', '', '', 'number', '1'),
('24', '2', '12', 'textarea', '客户的近况', '', '', '', '', '0'),
('25', '2', '13', 'hidden', '安全标识', 'Token', '', '21232f297a57a5a7', '', '0');

CREATE TABLE IF NOT EXISTS " . tablename('wjsw_form_data') . " (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(20) NOT NULL COMMENT '所属表单ID',
  `content` mediumtext NOT NULL COMMENT '内容',
  `addtime` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO " . tablename('wjsw_form_data') . " (`id`, `fid`, `content`, `addtime`) VALUES
 (3, 2, 'a:2:{s:5:\"title\";a:12:{i:0;s:6:\"姓名\";i:1;s:6:\"邮箱\";i:2;s:12:\"联系方式\";i:3;s:6:\"住址\";i:4;s:12:\"婚姻状况\";i:5;s:6:\"爱好\";i:6;s:6:\"职业\";i:7;s:12:\"邮政编码\";i:8;s:6:\"主页\";i:9;s:2:\"QQ\";i:10;s:9:\"通行令\";i:11;s:15:\"客户的近况\";}s:7:\"content\";a:12:{i:0;s:9:\"王老五\";i:1;s:13:\"test@sina.com\";i:2;s:11:\"18888888888\";i:3;s:12:\"北京故宫\";i:4;s:6:\"未婚\";i:5;a:3:{i:0;s:6:\"游戏\";i:1;s:6:\"看书\";i:2;s:6:\"旅游\";}i:6;s:12:\"天文学家\";i:7;s:6:\"057150\";i:8;s:18:\"http://www.yjk.com\";i:9;s:5:\"12345\";i:10;s:6:\"123456\";i:11;s:12:\"近来还行\";}}', 1576719122);
";
pdo_query($sql);