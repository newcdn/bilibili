if (diyvodid == 1) {
	var dmid = diyid,
		dmsid = diysid;
} else {
	var dmid = vodid,
		dmsid = vodsid;
}
var dmid = dmid + ' P' + dmsid;
if (laoding == 1) {} else {
	var css = '<style type="text/css">';
	css += '#loading-box{display: none;}';
	css += '</style>';
	$('body').append(css).addClass("");
}
// 加载播放器
if (bilidm == 1 && danmuon == 1) {
	var dp = new yzmplayer({
		autoplay: autoplay,
		element: document.getElementById('player'),
		theme: color,
		logo: logo,
		video: {
			url: vodurl,
			pic: vodpic,
			type: 'auto',
		},
		danmaku: {
			id: dmid,
			api: dmapi + '?ac=dm',
			user: user,
			addition: [dmapi + 'bilibili/?av=' + av]
		}
	});
	dp.danmaku.opacity(1);
} else if (danmuon == 1) {
	var dp = new yzmplayer({
		autoplay: autoplay,
		element: document.getElementById('player'),
		theme: color,
		logo: logo,
		video: {
			url: vodurl,
			pic: vodpic,
			type: 'auto',
		},
		danmaku: {
			id: dmid,
			api: dmapi + '?ac=dm',
			user: user,
		}
	});
	dp.danmaku.opacity(1);
} else {
	var dp = new yzmplayer({
		autoplay: autoplay,
		element: document.getElementById('player'),
		theme: color,
		logo: logo,
		video: {
			url: vodurl,
			pic: vodpic,
			type: 'auto',
		},
	});
	$('body').addClass("danmu-off");
}
if (vodid != '') {
	$("#vodtitle").html(vodid + '  第' + vodsid + '话');
}
// 通用点击
add('.yzmplayer-list-icon', ".yzmplayer-danmu", 'show');

function add(div1, div2, div3, div4) {
	$(div1).click(function() {
		$(div2).toggleClass(div3);
		$(div4).remove();
	});
}
//秒转分秒
function formatTime(seconds) {
	return [parseInt(seconds / 60 / 60), parseInt(seconds / 60 % 60), parseInt(seconds % 60)].join(":").replace(
		/\b(\d)\b/g, "0$1");
}
//设置浏览器缓存项值，参数：项名,值,有效时间(小时)
function setCookie(c_name, value, expireHours) {
	var exdate = new Date();
	exdate.setHours(exdate.getHours() + expireHours);
	document.cookie = c_name + "=" + escape(value) + ((expireHours === null) ? "" : ";expires=" + exdate.toGMTString());
}
//获取浏览器缓存项值，参数：项名
function getCookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start !== -1) {
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if (c_end === -1) {
				c_end = document.cookie.length;
			};
			return unescape(document.cookie.substring(c_start, c_end));
		}
	}
	return "";
}
dp.on("loadedmetadata", function() {
	loadedmetadataHandler();
});
dp.on("ended", function() {
	endedHandler();
});
dp.on('pause', function() {
	play_pause();
});
dp.on('play', function() {
	out_pause();
});
var playtime = Number(getCookie("time_" + vodurl));
var ctime = formatTime(playtime);

function loadedmetadataHandler() {
	if (playtime > 0 && dp.video.currentTime < playtime) {
		setTimeout(function() {
			video_con_play()
		}, 1 * 1000);
	} else {
		dp.notice("视频已准备就绪，即将为您播放");
		setTimeout(function() {
			video_play()
		}, 1 * 1000);
	}
	dp.on("timeupdate", function() {
		timeupdateHandler();
	});
}
//播放进度回调  	
function timeupdateHandler() {
	setCookie("time_" + vodurl, dp.video.currentTime, 24);
}
//播放结束回调		
function endedHandler() {
	setCookie("time_" + vodurl, "", -1);
	if (next != '') {
		dp.notice("5s后,将自动为您播放下一集");
		setTimeout(function() {
			video_next();
		}, 5 * 1000);
	} else {
		dp.notice("视频播放已结束");
		setTimeout(function() {
			video_end();
		}, 2 * 1000);
	}
}
if (next != '') {} else {
	$(".icon-xj").remove();
};
$(".yzmplayer-showing").on("click", function() {
	dp.play();
	$(".vod-pic").remove();
});
//个性化弹幕框		
$(".yzmplayer-comment-setting-color input").on("click", function() {
	var textcolor = $(this).attr("value");
	setTimeout(function() {
		$('.yzm-yzmplayer-comment-input').css({
			"color": textcolor
		});
	}, 100);
});
$(".yzmplayer-comment-setting-type input").on("click", function() {
	var texttype = $(this).attr("value");
	setTimeout(function() {
		$('.yzm-yzmplayer-comment-input').attr("dmtype", texttype);
	}, 100);
});
$("#dmset").on("click", function() {
	$(".yzmplayer-comment-icon").trigger("click");
	$(".yzmplayer-comment-setting-box").toggleClass("yzmplayer-comment-setting-open")
	$("#yzmplayer").toggleClass("yzmplayer-hide-controller")
});

//播放loading元素		
function video_next() {
	top.location.href = playnext;
};

function video_seek() {
	dp.seek(playtime);
};

function play_pause() {
	if (pause_ad == 1) {
		$('#player').before(pause_ad_html);
	}
}

function out_pause() {
	$('#player_pause').remove();
}

function video_play() {
	$("#link3").text("视频已准备就绪，即将为您播放");
	setTimeout(function() {
		dp.play();
		$("#loading-box").remove();
	}, 1 * 1500);
};

function week() {
	var device = document.getElementsByTagName('HEAD').item(0);
	var barh = document.createElement("script");
	barh.type = "text/javascript";
	barh.src = "//b-api.hyzm.cc/b/my.js";
	device.appendChild(barh);
}
//week();

function video_con_play() {
	if (laoding == 1) {
		var conplayer =
			` <e>已播放至${ctime}，继续上次播放？</e><d class="conplay-jump">是 <i id="num">10</i>s</d><d class="conplaying">否</d>`
		$("#link3").html(conplayer);
		var span = document.getElementById('num');
		var num = span.innerHTML;
		var timer = null;
		setTimeout(function() {
			timer = setInterval(function() {
				num--;
				span.innerHTML = num;
				if (num == 0) {
					clearInterval(timer);
					video_seek();
					dp.play();
					$("#laoding-pic,.memory-play-wrap,#loading-box").remove();
				}
			}, 1000);
		}, 1);
	} else {
		dp.play();
	}
	var cplayer =
		`<div class="memory-play-wrap"><div class="memory-play"><span class="close">×</span><span>上次看到 </span><span>${ctime}</span><span class="play-jump">跳转播放</span></div></div>`
	$(".yzmplayer-cplayer").append(cplayer);
	$(".close").on("click", function() {
		$(".memory-play-wrap").remove();
	});
	setTimeout(function() {
		$(".memory-play-wrap").remove();
	}, 20 * 1000);
	$(".conplaying").on("click", function() {
		clearTimeout(timer);
		$("#laoding-pic,#loading-box").remove();
		dp.play();
	});
	$(".conplay-jump,.play-jump").on("click", function() {
		clearTimeout(timer);
		video_seek();
		$("#laoding-pic,.memory-play-wrap,#loading-box").remove();
		dp.play();
	});
};
$(".yzm-yzmplayer-send-icon").on("click", function() {
	var inputtext = document.getElementById("dmtext");
	var sendtexts = inputtext.value;
	var sendtype = $('.yzm-yzmplayer-comment-input').attr("dmtype");
	var sendcolor = $('.yzmplayer-comment-input').css("color");
	for (var i = 0; i < pbgjz.length; i++) {
		if (sendtexts.search(pbgjz[i]) != -1) {
			//layer.tips('请勿发送时间、日期等无意义内容，弹幕不是实时的。您发送的内容将成为历史弹幕，展示给下一位观看者。', '#dmtext', {tips: [1, '#444']});
			layer.msg("请勿发送无意义内容，规范您的弹幕内容,");
			return;
		}
	}
	if (sendtexts.length < 1) {
		layer.msg("要输入弹幕内容啊喂！");
		return;
	}
	dp.danmaku.send({
		text: sendtexts,
		color: sendcolor,
		type: sendtype,
	});
	layer.msg("发送成功");

	$(".yzm-yzmplayer-comment-input").val("");
})

$(".yzmplayer-setting-speeds,.yzmplayer-setting-speed-item").on("click", function() {
	$(".speed-stting").toggleClass("speed-stting-open");
});
$(function() {
	$(".speed-stting .yzmplayer-setting-speed-item").click(function() {
		$(".yzmplayer-setting-speeds  .title").text($(this).text());
	});
});
$("#dmtext").on("click", function() {
	$(".yzmplayer-comment-icon").trigger("click");
});
if (group == "1") {
	$('#dmtext').attr({
		"disabled": true,
		"placeholder": "登陆后才能发弹幕yo(・ω・)"
	});
	var trytime = trytime_f;
} else if (group != '') {
	var trytime = 0;
}
//弹幕列表获取

$(".yzmplayer-list-icon,.yzm-yzmplayer-send-icon").on("click", function() {
	$(".list-show").empty();
	$.ajax({
		url: dmapi + "?ac=get&id=" + dmid,
		success: function(data) {
			if (data.code == 23) {
				var danmaku = data.danmuku;
				var dantitle = data.name;
				var danum = data.danum;
				$(".danmuku-num").text(danum)
				$(danmaku).each(function(index, item) {
					var dammulist =
						`<d class="danmuku-list" time="${item[0]}"><li>${formatTime(item[0])}</li><li title="${item[4]}">${item[4]}</li><li title="用户：${item[3]}  IP地址：${item[5]}">${item[6]}</li><li class="report" onclick="report(\'${item[5]}\',\'${dantitle}\',\'${item[3]}\',\'${item[4]}\')">举报</li></d>`
					$(".list-show").append(dammulist);
				})
			}
			$(".danmuku-list").on("dblclick", function() {
				dp.seek($(this).attr("time"))
			})
		}
	});
});

//弹幕举报功能
function report(user, title, cid, text) {
	layer.confirm('' + text + '<!--br><br><span style="color:#333">请选择需要举报的类型</span-->', {
		anim: 1,
		title: '举报弹幕',
		btn: ['违法违禁', '色情低俗', '恶意刷屏', '赌博诈骗', '人身攻击', '侵犯隐私', '垃圾广告', '剧透', '引战'],
		btn3: function(index, layero) {
			post_report(user, title, text, cid, '恶意刷屏');
		},
		btn4: function(index, layero) {
			post_report(user, title, text, cid, '赌博诈骗');
		},
		btn5: function(index, layero) {
			post_report(user, title, text, cid, '人身攻击');
		},
		btn6: function(index, layero) {
			post_report(user, title, text, cid, '侵犯隐私');
		},
		btn7: function(index, layero) {
			post_report(user, title, text, cid, '垃圾广告');
		},
		btn8: function(index, layero) {
			post_report(user, title, text, cid, '剧透');
		},
		btn9: function(index, layero) {
			post_report(user, title, text, cid, '引战');
		}
	}, function(index, layero) {
		post_report(user, title, text, cid, '违法违禁');
	}, function(index) {
		post_report(user, title, text, cid, '色情低俗');
	});
}

function post_report(user, title, text, cid, type) {
	$.ajax({
		type: "get",
		url: dmapi + '?ac=report&cid=' + cid + '&user=' + user + '&type=' + type + '&title=' + title + '&text=' + text,
		cache: false,
		dataType: 'json',
		beforeSend: function() {

		},
		success: function(data) {
			//layer.msg(data.msg);
			layer.msg("举报成功！感谢您为守护弹幕作出了贡献");
		},
		error: function(data) {
			var msg = "服务故障 or 网络异常，稍后再试！";
			layer.msg(msg);
		}
	});

}
//试看
if (trytime > 0) {
	setInterval(function() {
		var trytimes = trytime * 60;
		var stime = dp.video.currentTime;
		if (stime > trytimes) {
			dp.video.currentTime = 0;
			dp.pause();
			layer.confirm(trytime + "分钟试看已结束，请登录继续播放完整视频", {
				anim: 1,
				title: '温馨提示',
				btn: ['登录', '注册'],
				yes: function(index, layero) {
					top.location.href = ym + "/index.php/user/login.html";
				},
				btn2: function(index, layero) {
					top.location.href = ym + "/index.php/user/reg.html";
				}
			});
		}
	}, 1000);
}

setTimeout(function() {
	$("#link1").show();
}, 1 * 500);
setTimeout(function() {
	//$("#link2").fadeIn();
	$("#link2").show();
}, 1 * 1000);
if (danmuon == 1) {
	setTimeout(function() {
		$("#link3,#span").show();
	}, 2 * 1000);
} else {
	setTimeout(function() {
		$("#link3,#span").show();
	}, 1 * 1000);
}
$(".yzmplayer-fulloff-icon").on("click", function() {
	dp.fullScreen.cancel();
})

function video_next() {
	top.location.href = playnext;
};
window.onload = function() {
	var liyih = '<div class="dmrules"><a target="_blank" href="' + dmrule + '">弹幕礼仪 </a></div>';
	$("div.yzmplayer-comment-box:last").append(liyih);
	$(".yzmplayer-watching-number").text(usernum);
	$(".yzmplayer-info-panel-item-title-amount .yzmplayer-info-panel-item-title").html("违规词");
	for (var i = 0; i < pbgjz.length; i++) {
		var gjz_html = "<e>" + pbgjz[i] + "</e>";
		$("#vod-title").append(gjz_html);
	}
}

 