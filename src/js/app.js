$(function(){
	var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)')||0)[2];
	var $tbody = $('#list');
	//refresh si le hash change
	$(window).bind('hashchange',list).trigger('hashchange');
	//refreh la liste des fichiers toutes les minutes
	setInterval(list, 60000);

	$('.download, a.name').live('click', function(data){
		var name = $(this).attr('data-file');
		$.get("src/log.php?",{'type':'dl','text':'Download: "'+name+'"'},function(data){

		});
	});

	$('.delete').live('click',function(data) {
		var name = $(this).attr('data-file');
		if(confirm("Confirmer la suppression de "+name)){
			$.get("src/php/remove.php?",{'file':name},function(data){
				list();
			},'json');
		}
		return false;
	});
	
	/*$('.zip').live('click',function(data){
		$.get('?',{'do':'zip',file:$(this).attr('data-file')},function(data) {
		},'json');
		alert("Création du zip en cours...\nPeut prendre un certain temps...");
		window.location.reload();
	});*/

	$('.rename').live('click',function(){
		var file = $(this).attr('data-file');
		var path = file.split('\/');
		var old_name = path.pop();
		path = path.join('\/');
		if(path)
			path = path+'/';
		else
			path = './';

		var new_name = prompt("Rename:",old_name);

		if(new_name && new_name != old_name){
			new_name = new_name.split('\/').pop(); //remove moving option
			$.get('src/php/rename.php?',{'path': path, 'file':file, 'newname': new_name},function(data) {
				list();
			},'json');
		}
	});

	/*$('.move').live('click',function(){
		var file = $(this).attr('data-file');

	});*/

	$('.change-password').live('click',function(){
		var w = 300;
		var h = 300;
		var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    	var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

		var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    	var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
		
		var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    	var top = ((height / 2) - (h / 2)) + dualScreenTop;

		var newWindow = window.open("pass.php","","menubar=no, status=no, scrollbars=no, menubar=no, width="+w+", height="+h+", top="+top+", left="+left);

		if (window.focus) {
        	newWindow.focus();
    	}
	});

	$('.mkdir').live('click',function(){
		var path = $(this).attr('data-path');
		if(path)
			path = path+'/';
		var name = prompt("New folder name:");
		if(name){
			$.get('src/php/mkdir.php?',{'file':name,'path':path},function(data) {
				list();
			},'json');
		}
	});

	$('.ytdl').live('click', function(){
		var url = prompt("Url youtube à télécharger");
		if(url){
			$.get('src/php/ytdl.php?',{'url':encodeURIComponent(url)},function(data) {
				list();
				console.log(data);
			},'json');
		}
	});

	function list() {
		var hashval = window.location.hash.substr(1);
		$.get('?',{'do':'list','file':hashval},function(data) {
			$tbody.empty();
			$('#breadcrumb').empty().html(renderBreadcrumbs(hashval)).append('<div class="capacity"><i>~'+formatFileSize(data.total_size)+'</i></div>');
			if(data.success) {
				$.each(data.results,function(k,v){
					$tbody.append(renderFileRow(v));
				});
				!data.results.length && $tbody.append('<tr><td class="empty" colspan=5>This folder is empty</td</td>')
				data.is_writable ? $('body').removeClass('no_write') : $('body').addClass('no_write');
			} else {
				console.warn(data.error.msg);
			}

			//range dans l'ordre de modification decroissant
			$('#table').tablesortby(2,false);
			initDragDrop();
		},'json');

		$('.mkdir, .ytdl').attr('data-path',hashval).attr('href',"#"+window.location.hash.substr(1));

		if(window.location.hash.substr(1) == "Youtube"){
			$('.ytdl').show();
		} else {
			$('.ytdl').hide();
		}
		
	}

	function renderFileRow(data) {
		var fname = data.name.replace(/\[[^\[]*\]/, "");
		var $link = $('<a class="name" />')
			.attr('href', data.is_dir ? '#' + data.path : 'src/php/download.php?file='+data.path)
			.text((fname.length > 35) ? fname.substring(0,35)+'...' : fname)
			.attr('id',data.extension)
			.attr('data-file',data.path);
		var $dl_link = $('<a/>').attr('href','src/php/download.php?file='+data.path)
			.addClass('action download').text('download');

		/*var $zip = $('<a/>').attr('href','#').attr('data-file',data.path)
			.addClass('zip').text('zip');*/

		var $delete_link = $('<a/>').attr('data-file',data.path).addClass('action delete').text('delete');
				
		var $rename = $('<a/>').attr('data-file',data.path).addClass('action rename').text('rename');

		var $move = $('<a/>').attr('data-file',data.path).addClass('action move').text('move');

		var $html = $('<tr />')
			.addClass(data.is_dir ? 'is_dir' : 'is_file')
			.attr('data-file',data.path)
			.append( $('<td class="first" />').append($link) )
			.append( $('<td/>').attr('data-sort', data.size)
				.html($('<span class="size" />').text(formatFileSize(data.size))) ) 
			.append( $('<td/>').attr('data-sort',data.mtime).text(formatTimestamp(data.mtime)) )
			/*.append( $('<td/>').text(perms.join('+')) )*/
			.append( 
				$('<td/>').append($dl_link)
				.append(
					data.is_deleteable && data.path != "Series" && data.path != "Films" && data.path != "Softs" && data.path != "Youtube" ? 
						$delete_link : 
						$delete_link.addClass('no')
				)
				/*.append(
					data.extension != 'zip' ? 
						$zip : 
						$zip.addClass('no')
				)*/
				.append(
					data.is_writable && data.path != "Series" && data.path != "Films" && data.path != "Softs" && data.path != "Youtube" ?
					$rename :
					$rename.addClass('no')
				)
				.append(
					data.is_writable && data.path != "Series" && data.path != "Films" && data.path != "Softs" && data.path != "Youtube" ?
					$move : 
					$move.addClass('no')
				)
			)
		return $html;
	}

	function initDragDrop(){
		$('.is_dir, #breadcrumb div a').droppable({
			greedy: true,
    		drop : function(data){
    			var path = $(this).attr('data-file');
    			var file = $(data.srcElement).attr('data-file');
        		var name = file.split('\/').pop();

        		if(name != "Films" && name != "Series" && name != "Softs" && name != "Youtube"){
					if(path)
						path = path+'/';
					else
						path = './';

					$.get('src/php/move.php?',{'path': path, 'file':file, 'name': name},function(data) {
						list();
					},'json');
				}
    		}
		});

		$('#list tr .first .name').draggable({
    		revert : true,
    		drag: function() {
    			$('#list tr .first .name').not(this).removeClass('ui-draggable');
    		},
    		stop: function() {
    			initDragDrop();
    		}
		});
	}

	function renderBreadcrumbs(path) {
		var base = "",
			$html = $('<div/>').append( $('<a href=#>Home</a></div>') );
		$.each(path.split('/'),function(k,v){
			if(v) {
				$html.append( $('<span/>').text(' ▸ ') )
					.append( $('<a/>').attr('href','#'+base+v).text(v).attr('data-file',base+v) );
				base += v + '/';
			}
		});
		return $html;
	}
	function formatTimestamp(unix_timestamp) {
		var m = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		var d = new Date(unix_timestamp*1000);
		return [m[d.getMonth()],' ',d.getDate(),', ',d.getFullYear()," ",
			(d.getHours() % 12 || 12),":",(d.getMinutes() < 10 ? '0' : '')+d.getMinutes(),
			" ",d.getHours() >= 12 ? 'PM' : 'AM'].join('');
	}
	function formatFileSize(bytes) {
		var s = ['bytes', 'KB','MB','GB','TB','PB','EB'];
		for(var pos = 0;bytes >= 1000; pos++,bytes /= 1024);
		var d = Math.round(bytes*10);
		return pos ? [parseInt(d/10),".",d%10," ",s[pos]].join('') : bytes + ' bytes';
	}
})
