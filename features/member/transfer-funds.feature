Feature: Transfer funds to another member
  In order to send a gift or pay a debt
  As a member
  I want to be able to transfer funds to another member

  Scenario: Make a transfer with sufficient funds
    Given I have an account balance of "5000" MXN
    And my friend has an account balance of "4000" MXN
    When I transfer him "2000" MXN
    Then I should be notified that the transfer is complete
    And my balance should be "3000" MXN
    And my friend's balance should be "6000" MXN
