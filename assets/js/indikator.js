function lockBrowser(){
	$.blockUI({css: {
			border: "none",
			padding: "15px",
			'border-radius': '10px',
			backgroundColor: "#000",
			'-webkit-border-radius': "10px",
			'-moz-border-radius': "10px",
			opacity: .5,
			color: "#fff"
		},
    baseZ: 9999,//z-index
    message: "<h1><img src=\"" + base_url + "assets/img/loaders/loading-black.gif\" width='30' height='30'> Harap tunggu...</h1>",
	});
}

function unlockBrowser(){
	$.unblockUI();
}

function indikatorSibuk(){
	$.blockUI({css: {
			opacity: 0,
		},
    baseZ: 1040,//z-index
	});
}

function indikatorStop(){
	$.unblockUI();
}

function showOverhang(type,msg){
	$("body").overhang({
		type: type,
		message: msg
	});
}

//type : success,error,info,warning,question
function swalFireBasic(title,text,type){
  Swal.fire({
    type: type,
    title: title,
    text: text
  })
}

// Modal window position, can be 'top', 'top-start', 'top-end', 'center', 'center-start', 'center-end', 'bottom', 'bottom-start', or 'bottom-end'.
function swalFireStatus(title,type){
	Swal.fire({
	  position: 'center',
	  type: type,
	  title: title,
	  showConfirmButton: false,
	  timer: 1500
	})
}

function swalFireStatusWithTimer(title,type){
	let timerInterval;
	Swal.fire({
		type: type,
	  title: title,
	  html: 'You will be redirecting in <b></b> milliseconds.',
	  timer: 2000,
	  timerProgressBar: true,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
	  onBeforeOpen: () => {
	    Swal.showLoading()
	    timerInterval = setInterval(() => {
	      const content = Swal.getContent()
	      if (content) {
	        const b = content.querySelector('b')
	        if (b) {
	          b.textContent = Swal.getTimerLeft()
	        }
	      }
	    }, 100)
	  },
	  onClose: () => {
	    clearInterval(timerInterval);
	  }
	}).then((result) => {
	  /* Read more about handling dismissals below */
	  if (result.dismiss === Swal.DismissReason.timer) {
	    console.log('I was closed by the timer')
	  }
	})
}

function swalFireStatusRedirectWithTimer(title,type,url){
	let timerInterval;
	Swal.fire({
		type: type,
	  title: title,
	  html: 'You will be redirecting in <b></b> milliseconds.',
	  timer: 2000,
	  timerProgressBar: true,
		allowOutsideClick: false,
		allowEscapeKey: false,
		allowEnterKey: false,
	  onBeforeOpen: () => {
	    Swal.showLoading()
	    timerInterval = setInterval(() => {
	      const content = Swal.getContent()
	      if (content) {
	        const b = content.querySelector('b')
	        if (b) {
	          b.textContent = Swal.getTimerLeft()
	        }
	      }
	    }, 100)
	  },
	  onClose: () => {
	    clearInterval(timerInterval);
			window.location = url;
	  }
	}).then((result) => {
	  /* Read more about handling dismissals below */
	  if (result.dismiss === Swal.DismissReason.timer) {
	    console.log('I was closed by the timer')
	  }
	})
}

//type : success,error,info,warning,question
function swalToast(title,type){
	const Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 1500
    });

	Toast.fire({
    type: type,
    title: title
  })
}

function toastrFire(title,type){
	toastr.options = {
      "closeButton": true,
      "debug": false,
      "positionClass": "toast-top-right",
      "onclick": null,
			"tapToDismiss" : true,
      // "showDuration": "100",
      // "hideDuration": "1000",
      // "timeOut": "300000",
      // "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut",
  };

	if(type==='success'){
		toastr.success(title);
	}else if(type==='info'){
		toastr.info(title);
	}else if(type==='error'){
		toastr.error(title);
	}else{
		toastr.warning(title);
	}
}
