// Seat selector logic used on booking.php
document.addEventListener('DOMContentLoaded', function () {
  const seatGrid = document.getElementById('seatGrid');
  if (!seatGrid) return;

  const seatsInput = document.getElementById('selectedSeats');
  const seatCountEl = document.getElementById('seatCount');
  const totalEl = document.getElementById('totalAmount');
  const fare = parseFloat(seatGrid.dataset.fare || '0');

  const selected = new Set();

  seatGrid.querySelectorAll('.seat:not(.booked)').forEach(el => {
    el.addEventListener('click', () => {
      const n = el.dataset.seat;
      if (selected.has(n)) {
        selected.delete(n);
        el.classList.remove('selected');
      } else {
        if (selected.size >= 6) {
          alert('You can book maximum 6 seats per booking.');
          return;
        }
        selected.add(n);
        el.classList.add('selected');
      }
      seatsInput.value = Array.from(selected).join(',');
      seatCountEl.textContent = selected.size;
      totalEl.textContent = '₹' + (selected.size * fare).toFixed(2);
    });
  });

  const form = document.getElementById('bookingForm');
  if (form) {
    form.addEventListener('submit', e => {
      if (selected.size === 0) {
        e.preventDefault();
        alert('Please select at least one seat.');
      }
    });
  }
});

// Set min date for date inputs to today
document.querySelectorAll('input[type=date]').forEach(d => {
  if (!d.min) d.min = new Date().toISOString().split('T')[0];
});
