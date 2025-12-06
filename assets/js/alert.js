const Toast = Swal.mixin({
    toast: true,
    position: "top-left",
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});


// Swal.fire({
//   title: '¡Que Hacel Vago!',
//   text: 'tlabaja , tu tiene que tlabaja!',
//   icon: 'error',
//   confirmButtonText: 'Cool' })




