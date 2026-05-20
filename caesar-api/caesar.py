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
# ALGORITMA CAESAR (UPDATED: SUPPORTS LETTERS & NUMBERS)
# =========================
def caesar_encrypt(plaintext: str, shift: int = 3) -> str:
    result = ""

    for char in plaintext:
        if char.isalpha():
            # Handle alphabetic characters (A-Z, a-z)
            base = 65 if char.isupper() else 97
            result += chr((ord(char) - base + shift) % 26 + base)
        elif char.isdigit():
            # Handle numeric characters (0-9)
            result += chr((ord(char) - 48 + shift) % 10 + 48)
        else:
            # Keep non-alphanumeric characters unchanged
            result += char

    return result


def caesar_decrypt(ciphertext: str, shift: int = 3) -> str:
    result = ""

    for char in ciphertext:
        if char.isalpha():
            # Handle alphabetic characters (A-Z, a-z)
            base = 65 if char.isupper() else 97
            result += chr((ord(char) - base - shift) % 26 + base)
        elif char.isdigit():
            # Handle numeric characters (0-9)
            result += chr((ord(char) - 48 - shift) % 10 + 48)
        else:
            # Keep non-alphanumeric characters unchanged
            result += char

    return result


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