// --- toast.js ---
// 🔥 Configurar Toast global de SweetAlert2
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000, // 3 segundos
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer);
      toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
  });
  
  // 🔥 Función reutilizable para mostrar Toasts
  export function showToast({ text, type = 'success' }) {
    Toast.fire({
      icon: type, // success, error, warning, info, question
      title: text
    });
  }
  