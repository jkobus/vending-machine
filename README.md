# Vending Machine Coding Challenge

## The challenge

Design a vending machine that will take a number of coins and return change in the smallest number of coins possible. 
The machine should take 6 different coin types: 50p, 20p, 10p, 5p, 2p, 1p.
There are 3 products: A - 95p, B - £1.26, C - £2.33.

Write tests for your solution using a testing framework of your choice.

## The solution

It is a bit over-engineered and done for fun, feel free to submit your PRs with improvements. 

Implemented features:

- [x] Accepts coins
- [x] Returns change in the smallest number of coins possible
- [x] Accepts 6 different coin types: 50p, 20p, 10p, 5p, 2p, 1p
- [x] When not picked up, the coins are stacked up in the coin return
- [x] Unsupported coins are returned in the coin return

## How to run

Install php 8.2 and composer, then run:

```bash
composer install
```

To run the tests:

```bash
composer test
```

