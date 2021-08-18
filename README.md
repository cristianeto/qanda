# QandA Program

The Best interactive CLI program for Question and Answer practice, _ever!_.

## Features
- Create a question
- List all questions
- Practice
- Stats
- Reset
- Exit

## Config 

1. This program is using Sail to containerize this Laravel Application, so execute the following command.
`./vendor/bin/sail up`.
        
2. Migrating tables and so on.
`./vendor/bin/sail artisan migrate`.

## How to use

The following instructions will help to run this program. 

**1. Start the program**

Basically, there are **2 ways** to interact with it.
The first way is as a guest, and the second option is as a specific user.

Running the Program CLI **as a GUEST**.
`./vendor/bin/sail artisan qanda:interactive`.
The previous command allows you to interact with CLI program as a guest user. 
    
**or** Running the Program CLI **as a specific user**.
`./vendor/bin/sail artisan qanda:interactive cris@test.com`.
The previous command allows you to interact with CLI program as a specific user, in this case as "cris@test.com".

**2. Creating a Question**


## Testing
`./vendor/bin/sail artisan test`
