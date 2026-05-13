"""
Caesar Cipher CLI — Terminal Interface for Caesar Cipher

Usage:
    python cli.py                 → Interactive mode
    python cli.py encrypt TEXT [SHIFT]  → Encrypt text with optional shift
    python cli.py decrypt TEXT [SHIFT]  → Decrypt text with optional shift

The default shift is 3.
"""

import sys

from caesar import caesar_encrypt, caesar_decrypt


def banner():
    print("""
Caesar Cipher CLI
=================
Classic substitution cipher for educational use.
""")


def print_result(label: str, value: str) -> None:
    print(f"{label}: {value}")


def do_encrypt(plaintext: str, shift: int = 3) -> str:
    ciphertext = caesar_encrypt(plaintext, shift)
    print_result("Plaintext", plaintext)
    print_result("Shift", str(shift))
    print_result("Ciphertext", ciphertext)
    return ciphertext


def do_decrypt(ciphertext: str, shift: int = 3) -> str:
    plaintext = caesar_decrypt(ciphertext, shift)
    print_result("Ciphertext", ciphertext)
    print_result("Shift", str(shift))
    print_result("Plaintext", plaintext)
    return plaintext


def interactive() -> None:
    banner()
    print("Enter commands: encrypt, decrypt, or exit")

    while True:
        try:
            command = input("command> ").strip().lower()
        except (KeyboardInterrupt, EOFError):
            print("\nBye!")
            break

        if command in ("exit", "quit", "q", "0"):
            print("Bye!")
            break

        if command.startswith("encrypt"):
            parts = command.split()
            if len(parts) < 2:
                print("Usage: encrypt TEXT [SHIFT]")
                continue

            shift = 3
            if len(parts) > 2 and parts[-1].lstrip("+-").isdigit():
                try:
                    shift = int(parts[-1])
                    text = " ".join(parts[1:-1])
                except ValueError:
                    print("Shift harus angka")
                    continue
            else:
                text = " ".join(parts[1:])

            if not text:
                print("Usage: encrypt TEXT [SHIFT]")
                continue

            do_encrypt(text, shift)

        elif command.startswith("decrypt"):
            parts = command.split()
            if len(parts) < 2:
                print("Usage: decrypt TEXT [SHIFT]")
                continue

            shift = 3
            if len(parts) > 2 and parts[-1].lstrip("+-").isdigit():
                try:
                    shift = int(parts[-1])
                    text = " ".join(parts[1:-1])
                except ValueError:
                    print("Shift harus angka")
                    continue
            else:
                text = " ".join(parts[1:])

            if not text:
                print("Usage: decrypt TEXT [SHIFT]")
                continue

            do_decrypt(text, shift)

        else:
            print("Perintah tidak dikenal. Gunakan encrypt, decrypt, atau exit.")


def main() -> None:
    if len(sys.argv) == 1:
        interactive()
        return

    command = sys.argv[1].lower()
    if command == "encrypt":
        if len(sys.argv) < 3:
            print("Usage: python cli.py encrypt TEXT [SHIFT]")
            sys.exit(1)
        text = sys.argv[2]
        shift = int(sys.argv[3]) if len(sys.argv) > 3 else 3
        do_encrypt(text, shift)
    elif command == "decrypt":
        if len(sys.argv) < 3:
            print("Usage: python cli.py decrypt TEXT [SHIFT]")
            sys.exit(1)
        text = sys.argv[2]
        shift = int(sys.argv[3]) if len(sys.argv) > 3 else 3
        do_decrypt(text, shift)
    else:
        print("Unknown command.")
        print("Usage: python cli.py [encrypt|decrypt] TEXT [SHIFT]")
        sys.exit(1)


if __name__ == "__main__":
    main()
