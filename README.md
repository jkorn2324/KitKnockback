# KitKnockback

### Description
This is a two-in-one PocketMine/Genysis plugin that allows players to add kits to their server, while also being able to change the knockback according to the kit the player has. This plugin is only available for Minecraft Versions 0.16 and 0.15.

## Table of Contents
[TOC]

### Features

- Create kits based on the items found within a players inventory.
- Edit the knockback of the player based on a kit that each player has.
- [Advanced1vs1](http://https://github.com/jkorn2324/Advanced1vs1 "Advanced1vs1") Compatibility.

### Commands

- `/kit-create <name>` - Creates a kit based on all of the items in your inventory and the effects the player currently has enabled.
	- `<name>` - Parameter that determines the name of the kit.
- `/kit-delete <name>` - Deletes a kit based on the name.
	- `<name>` - Parameter that determines which kit to delete.
- `/kbinfo <name>` - Lists the knockback information based on the kit name.
	- `<name>` - Parameter that determines the name of the kit to display the information to.
- `/listkits` - Lists all of the kits within the server.
- `/kit <name>` - Gives the kit to the player.
	- `<name>` - The parameter that determines which kit to give to the player.
- `/kb-x <name> <value>` - The command that allows players to edit the x-knockback of the player (how far away they go when they are hit).
	- `<name>` - The parameter that determines which kit to edit the x-knockback.
	- `<value>` - The parameter that determines the value of the x-knockback the kit should have. The default x-knockback value is 0.4.
- `/kb-y <name> <value>` - The command that allows players to edit the y-knockback of the player (how high the player goes each hit).
	- `<name>` - The parameter that determines which kit to edit the y-knockback.
	- `<value>` - The parameter that determines the value of the y-knockback the kit should have. The default y-knockback value is 0.4.
	- `/kb-speed <name> <value>` - The command that allows players to edit the knockback speed of the player (how fast the delay is between each hit).
	- `<name>` - The parameter that determines which kit to edit the knockback speed.
	- `<value>` - The parameter that determines the value of the knockback speed the kit should have. The default knockback speed value is 10.

### Permissions
- `permission.kit` - Allows players to create and delete kits. By default only operators can use commands that contain this permission.
- `permission.kit.list` - Allows players to view the list of the kits. By default all players can use commands with this permission.
- `permission.kit.kb` - Allows players to edit the knockback of the kits. By default only operators can use this command.
- `permission.kit.kbinfo` - Allows players to view the knockback information of a kit. By default only operators can use this command.
