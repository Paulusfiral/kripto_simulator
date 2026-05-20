"""
Quick unit tests for the Caesar Cipher implementation.
"""

from caesar import caesar_encrypt, caesar_decrypt


def test_basic_encrypt():
    assert caesar_encrypt("ABC", 3) == "DEF"


def test_basic_decrypt():
    assert caesar_decrypt("DEF", 3) == "ABC"


def test_mixed_case_preserves_case():
    assert caesar_encrypt("Hello, World!", 3) == "Khoor, Zruog!"


def test_digits_are_shifted():
    encrypted = caesar_encrypt("p4aulus", 3)
    assert encrypted == "s7dxoxv"
    assert caesar_decrypt(encrypted, 3) == "p4aulus"


def test_non_alphanumeric_unchanged():
    assert caesar_encrypt("!@#", 5) == "!@#"


def test_roundtrip_all_shifts():
    original = "The Quick Brown Fox Jumps Over The Lazy Dog!"
    for shift in range(26):
        encrypted = caesar_encrypt(original, shift)
        decrypted = caesar_decrypt(encrypted, shift)
        assert decrypted == original


if __name__ == "__main__":
    test_basic_encrypt()
    test_basic_decrypt()
    test_mixed_case_preserves_case()
    test_digits_are_shifted()
    test_non_alphanumeric_unchanged()
    test_roundtrip_all_shifts()
    print("All Caesar unit tests passed!")
