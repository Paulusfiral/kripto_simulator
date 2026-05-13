"""
Quick API integration test for the Caesar Cipher microservice.

Run after the service is started:
    python -m uvicorn main:app --host 127.0.0.1 --port 8002
"""

import json
import urllib.request

BASE_URL = "http://127.0.0.1:8002"


def api_get(path):
    resp = urllib.request.urlopen(f"{BASE_URL}{path}")
    return json.loads(resp.read().decode("utf-8"))


def api_post(path, body):
    data = json.dumps(body).encode("utf-8")
    req = urllib.request.Request(
        f"{BASE_URL}{path}",
        data=data,
        headers={"Content-Type": "application/json"},
    )
    resp = urllib.request.urlopen(req)
    return json.loads(resp.read().decode("utf-8"))


print("=== GET / ===")
info = api_get("/")
print(f"service: {info.get('service')}")
print(f"version: {info.get('version')}")
print()

print("=== POST /encrypt ===")
enc = api_post("/encrypt", {"plaintext": "Halo Caesar!", "shift": 5})
print(f"ciphertext: {enc['ciphertext']}")
assert enc["plaintext_length"] == len("Halo Caesar!")
assert enc["ciphertext_length"] == len(enc["ciphertext"])
print()

print("=== POST /decrypt ===")
dec = api_post("/decrypt", {"ciphertext": enc["ciphertext"], "shift": 5})
print(f"plaintext: {dec['plaintext']}")
assert dec["plaintext"] == "Halo Caesar!"
print()

print("All API tests passed! ✅")
