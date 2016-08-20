Feature: Transfer funds
  In order to share funds with one of my recipients
  As a sender
  I want to be able to transfer funds to her

  Scenario: Sender has sufficient funds
    Given a sender with an account balance of "5000" MXN
    And a recipient with an account balance of "4000" MXN
    When the sender transfers "2000" MXN to the recipient
    Then the sender is notified that the transfer is complete
    And the sender's balance should be "3000" MXN
    And the recipient's balance should be "6000" MXN
