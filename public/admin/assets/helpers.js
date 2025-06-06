export function fetchJSON(url, jwt) {
  return fetch(url, { headers: { Authorization: `Bearer ${jwt}` } })
    .then(r => r.json().then(d => { if (!r.ok) throw new Error(d.error || 'Error'); return d; }));
}
export function postJSON(url, data, jwt) {
  return fetch(url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${jwt}` },
    body: JSON.stringify(data)
  }).then(r => r.json().then(d => { if (!r.ok) throw new Error(d.error || 'Error'); return d; }));
}
export function putJSON(url, data, jwt) {
  return fetch(url, {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${jwt}` },
    body: new URLSearchParams(data)
  }).then(r => r.json().then(d => { if (!r.ok) throw new Error(d.error || 'Error'); return d; }));
}
export function deleteReq(url, jwt) {
  return fetch(url, { method: 'DELETE', headers: { Authorization: `Bearer ${jwt}` } })
    .then(r => r.json().then(d => { if (!r.ok) throw new Error(d.error || 'Error'); return d; }));
}
