/* Copyright (C) Rivas System, Inc. All rights reserved. */
(function($){
	window.NarinDataViewer = new function(){

		var settings = {
			postUrl: 'dataviewerdemo_ajax.php'
		};

		var NarinDataViewer = this;

		NarinDataViewer.setForm = function(name) {
			settings.form = name;
		};

		$(document).ready(function(){

			$('.narindataviewer th .sort a').click(function(e){
				e.preventDefault();
				var $this = $(this);
				var form = $this.closest('form');
				form.find('[name="order_by"]').val($this.closest('th').data('col'));
				form.find('[name="order_type"]').val($this.hasClass('asc') ? 'asc' : 'desc');
				form.submit();
			});

			$('.narindataviewer .pagination a[data-page]').click(function(e){
				e.preventDefault();
				var $this = $(this);
				var form = $this.closest('form');
				form.find('[name="page"]').val($this.data('page'));
				form.submit();
			});

			$('.narindataviewer [name="results_per_page"]').change(function(){
				var form = $(this).closest('form');
				form.find('[name="page"]').val('');
				form.submit();
			});

			$('.narindataviewer .approval select').change(function(){
				var $this = $(this);
				var data = {form: settings.form, id: $this.closest('tr').data('id'), status: $this.val()};
				var icon = $this.prev();
				icon.attr('class', 'loading');
				$.post(settings.postUrl, data, function(){
					icon.attr('class', 'success');
				}).fail(function(){
					icon.attr('class', 'fail');
				});
			});

			$('.narindataviewer .approval input').keypress(function(e){
				if (e.which === 13) {
					e.preventDefault();
					var $this = $(this);
					var data = {form: settings.form, id: $this.closest('tr').data('id'), msg: $.trim($this.val())};
					var icon = $this.prev();
					icon.attr('class', 'loading');
					$.post(settings.postUrl, data, function(){
						icon.attr('class', 'success');
					}).fail(function(){
						icon.attr('class', 'fail');
					});
				}
			});

		});
	};
})(jQuery);