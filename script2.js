document.addEventListener("DOMContentLoaded", function () {
    const adultInput = document.getElementById("adults");
    const childInput = document.getElementById("children");
    const totalAmount = document.getElementById("totalAmount");

    function calculateTotal() {
        const adultTickets = parseInt(adultInput.value) || 0;
        const childTickets = parseInt(childInput.value) || 0;
        const total = (adultTickets * 1200) + (childTickets * 600);
        totalAmount.textContent = `Total: ₹${total}`;
    }

    adultInput.addEventListener("input", calculateTotal);
    childInput.addEventListener("input", calculateTotal);
});
