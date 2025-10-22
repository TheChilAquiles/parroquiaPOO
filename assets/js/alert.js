const Toast = Swal.mixin({
    toast: true,
    position: "center",
    showConfirmButton: false,
    timer: 7000,
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




