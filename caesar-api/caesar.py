"""
Caesar Cipher — FastAPI Microservice
"""

from fastapi import FastAPI
from pydantic import BaseModel

app = FastAPI()

# =========================
# DATA MODEL (REQUEST BODY)
# =========================
class EncryptRequest(BaseModel):
    plaintext: str
    shift: int = 3

class DecryptRequest(BaseModel):
    ciphertext: str
    shift: int = 3


# =========================
# ALGORITMA CAESAR
# =========================
def caesar_encrypt(plaintext: str, shift: int = 3) -> str:
    result = ""

    for char in plaintext:
        if char.isalpha():
            base = 65 if char.isupper() else 97
            result += chr((ord(char) - base + shift) % 26 + base)
        else:
            result += char

    return result


def caesar_decrypt(ciphertext: str, shift: int = 3) -> str:
    return caesar_encrypt(ciphertext, -shift)


# =========================
# ENDPOINT API
# =========================
@app.get("/")
def root():
    return {
        "service": "Caesar Cipher Microservice",
        "status": "running"
    }


@app.post("/encrypt")
def encrypt(req: EncryptRequest):
    result = caesar_encrypt(req.plaintext, req.shift)

    return {
        "plaintext": req.plaintext,
        "shift": req.shift,
        "ciphertext": result
    }


@app.post("/decrypt")
def decrypt(req: DecryptRequest):
    result = caesar_decrypt(req.ciphertext, req.shift)

    return {
        "ciphertext": req.ciphertext,
        "shift": req.shift,
        "plaintext": result
    }