document.addEventListener("DOMContentLoaded", () => {
  if (!Array.isArray(window.deviceData)) return;

  const matched = window.deviceData.find(
    ({ url }) => url === window.location.pathname
  );
  if (!matched) return;

  const { price, updated, availability } = matched;
  const $ = (s) => document.querySelector(`#device-info ${s}`);
  const formatPrice = (v) => new Intl.NumberFormat("uk-UA").format(v);

  const elPrice = $("#price");
  const elUpdated = $("#updated");
  const elAvailability = $("#availability");

  [
    [elPrice, formatPrice(price)],
    [elUpdated, updated],
    [elAvailability, availability],
  ].forEach(([el, value]) => {
    if (el) el.textContent = value;
  });
});
