/**球队表*/
create table `live_team`(
`id` tinyint(1)  unsigned not null auto_increment,
`name` varchar(20) not null default '' comment '球队名称',
`image` varchar(100) not null default '' comment '球队图片',
`type` tinyint(1) unsigned not null default 0 comment '球队所属部---东部、西部',
`create_time` int(10) unsigned not null default 0 comment '球队创建时间',
`update_time` int(10) unsigned not null default 0 comment '球队信息更新时间',
 primary key(`id`)
)engine=innoDB  auto_increment=1 default charset=utf8;

/** 直播表*/
create table `live_game`(
`id` int(10) unsigned not null auto_increment,
`a_id`  tinyint(1) unsigned not null default 0 comment 'A队id',
`b_id` tinyint(1) unsigned  not null default 0 comment  'B队id',
`a_score` int(10) unsigned not null default 0 comment 'A队的比分',
`b_score` int(10) unsigned not null default 0 comment 'B队的比分',
`narrator` varchar(20) not null default '' comment '解说员',
`image` varchar(100) not null default '',
`start_time` int(10) unsigned not null default 0 comment '开始时间',
`status` tinyint(1) unsigned not null default 0 comment '当前比赛状态',
`create_time` int(10) unsigned not null default 0,
`update_time` int(10) unsigned not null default 0,
 primary key(`id`)
)engine=innoDB auto_increment=1 default charset=utf8;


/** 球员表*/
create table `live_player`(
`id` int(10) unsigned not null auto_increment,
`name` varchar(20) not null default '' comment '球员名称',
`image` varchar(100)  not null  default '' comment '球员图片',
`age` tinyint(1) unsigned not null default 0 comment '球员年龄',
`position` tinyint(1) unsigned not null default 0 comment '主打位置',
`status` tinyint  unsigned not null default 0 comment '当前状态 退役、在职',
`create_time` int(10) unsigned not null default 0,
`update_time` int(10) unsigned not null default 0,
 primary key(`id`)
)engine=innoDB auto_increment=1 default charset=utf8;

/**赛事赛况表*/
create table `live_outs`(
`id` int(10) unsigned not null auto_increment,
`game_id` int(10) unsigned not null default 0 comment '比赛id',
`team_id` tinyint(1) unsigned not null default 0 comment '球队id',
`content`  varchar(200) not null default ''  comment '赛况内容',
`image` varchar(100) not null default '' comment '球队照片',
`type` tinyint(1) unsigned not null default 0 comment '比赛节数  1,2,3,4',
`status` tinyint(1) unsigned not null default 0 comment '当前状态',
`create_time` int(10) unsigned not null default 0,
 primary key(`id`)
)engine=innoDB auto_increment=1 default charset=utf8;
 

/** 聊天室表*/
create table `live_chart`(
`id` int(10) unsigned not null auto_increment,
`game_id` int(10) unsigned not null default 0,
`user_id` tinyint(1) unsigned not null default 0,
`content` varchar(200) not null default '',
`status`  tinyint(1) unsigned not null default 0,
`create_time` int(10) unsigned not null default 0,
 primary key(`id`)
)engine=innoDB auto_increment=1 default charset=utf8;