## Tutorial 2: UV Unwrapping a Game Asset

**Title:** UV Unwrapping a Game Asset in Blender  
**Slug:** `blender-uv-unwrapping-game-asset`  
**Category:** UV Mapping & Texturing  
**Difficulty:** Beginner  
**Time:** 40 minutes  
**Software:** Blender 4.x  
**Blender Version:** 4.2 LTS  
**Prerequisites:** You should have completed the low-poly barrel tutorial, or have a simple low-poly mesh ready. Familiarity with Edit Mode and basic vertex/edge selection is assumed.  
**Next Tutorial URL:** /tutorials/game-engine-export/blender-to-unity-fbx/  

**Excerpt:**  
Learn how to UV unwrap a game-ready prop in Blender. This tutorial covers seam placement, UV island generation, layout, and packing - using the low-poly barrel as a practical example.

---

## What you will learn

After this tutorial, you will be able to:
- Place seams on a 3D mesh so that it unfolds cleanly
- Generate UV islands from those seams using Blender's Unwrap operator
- Understand what UV islands represent and why their arrangement matters
- Pack islands efficiently to use texture space without waste
- Verify that your UV layout is ready for texturing

This tutorial uses the barrel from the [Low-Poly Barrel tutorial](/tutorials/low-poly-modeling/low-poly-barrel-blender/). If you completed that tutorial, open that file. If you are starting here, any simple cylindrical or box-shaped mesh will work.

## What UV unwrapping does

When you apply a texture image to a 3D object, Blender needs to know which part of the image goes on which polygon. UV coordinates are the mapping that makes this possible: they tell each vertex on the 3D mesh which point on the 2D texture image corresponds to it.

"UV" refers to the two axes of the 2D space - U for horizontal, V for vertical - since X, Y, and Z are already taken by 3D space.

Without UV coordinates, Blender has to guess. The guesses are often wrong. Unwrapping gives you explicit control.

## Why seams matter

To unfold a 3D surface into a flat 2D image without distortion, you need to cut it open along certain edges - similar to cutting along the edges of a cardboard box to lay it flat. These cuts are called **seams**.

Where you place seams affects two things:
1. **Distortion** - whether the UVs stretch or compress the texture
2. **Continuity** - whether texture patterns can flow across an edge without a visible seam in the final render

A good seam is placed where it will be least visible on the finished model: along a bottom edge, inside a concave fold, or on a part of the surface unlikely to face the camera.

## Step 1 - Open the UV Editor alongside the 3D Viewport

Before placing seams, set up your workspace so you can see the UV map as you work.

In the top menu bar, click the **UV Editing** workspace tab. Blender will split the screen into the 3D Viewport on the right and the UV Editor on the left. If you do not see this tab, you can add it from the menu at the top right of the window.

Select your barrel mesh and press Tab to enter Edit Mode. Press A to select all vertices. The UV Editor on the left will show whatever UV coordinates the mesh currently has - likely a default unwrap that is not useful yet.

## Step 2 - Place seams on the barrel

In the 3D Viewport (right side), switch to Edge Select mode by pressing 2 on the keyboard.

Think about how the barrel's surface divides into natural sections:
- The **top cap** - a flat circle
- The **bottom cap** - a flat circle
- The **side** - a curved band connecting the two caps

Each of these should become its own UV island. To separate them, you need to cut where the cap meets the side.

**Select the top rim edge loop:** Alt + click on any edge of the top rim where the cap meets the side. This selects the entire horizontal loop.

Mark it as a seam: go to **Edge → Mark Seam** (or right-click → Mark Seam). The selected edges will turn red to indicate they are seams.

**Repeat for the bottom rim:** Alt + click the bottom rim edge loop and mark it as a seam.

**Add a vertical seam on the side:** The cylindrical side of the barrel is still a closed loop. Blender cannot unfold a closed cylinder without one more cut. Select a single vertical edge on the back of the barrel (the side least likely to face the camera) and mark it as a seam.

You now have three seams: two horizontal (at the cap rims) and one vertical (on the back of the side).

## Step 3 - Unwrap the mesh

Press A to select all vertices. Then go to **UV → Unwrap** (or press U → Unwrap) in the 3D Viewport header.

Look at the UV Editor on the left. You should now see three separate UV islands:
- The top cap, unfolded into a flat circle
- The bottom cap, unfolded into a flat circle
- The side band, unfolded into a tall rectangle

If you see something messy - all faces overlapping, or islands that look distorted - the most common cause is a seam that did not connect properly. Go back and check that your seams form complete closed loops where they need to, and that the vertical seam extends the full height of the barrel side.

## Step 4 - Check for UV distortion

In the UV Editor, you can display a checker pattern to check for distortion. Look for the **Image** menu in the UV Editor header and open a new checker image, or in the 3D Viewport (with Edit Mode active) go to **Viewport Shading → Material Preview** (press Z → Material Preview) and assign a UV Grid texture to the barrel's material: in the Shader Editor, add an **Image Texture** node, click **New**, set Type to **UV Grid**, and connect it to the **Base Color** of the Principled BSDF.

With the checker visible in the 3D Viewport, check the barrel's surface in Edit Mode. If the squares are even and consistent in size across the surface, the UV layout is not distorting the texture. If the squares look stretched in any direction, the UVs need adjustment.

For a barrel's top cap, some radial compression toward the center is acceptable - this is inherent to mapping a circle to a square. The side should show very little distortion since it unfolds naturally.

## Step 5 - Scale and arrange the UV islands

By default, Blender places UV islands in a roughly equal arrangement. For a game asset, you may want to give more UV space to the faces that will be most visible.

In the UV Editor with Edit Mode active, press A to select all UVs. Then press S to scale the islands up. Keep them within the 0–1 UV square (the bright square in the UV Editor).

If the barrel will appear at a distance in the game, the caps (top and bottom) can be smaller relative to the side, since they are less visible. Select the two cap islands and scale them down slightly.

Press G to grab and move individual islands into a clean arrangement. There should be no overlapping islands - overlapping UV islands cause texture baking problems and often produce incorrect results in the game engine.

## Step 6 - Pack UV islands

Blender can automatically pack the islands to fill the UV space efficiently.

Go to **UV → Pack Islands** in the UV Editor header. Blender will rearrange the islands so they fill the UV square as densely as possible without overlapping.

After packing, check:
- No islands extend outside the 0–1 UV space (the bright square)
- Islands are not rotated in ways that will make texturing more difficult (for example, the barrel side should stay as a vertical rectangle, not be rotated 90 degrees)

If the packing result is awkward, you can undo it and arrange the islands manually instead.

## Step 7 - What is texel density, and does it matter here?

**Texel density** is the number of texture pixels (texels) per unit of surface area on the model. Consistent texel density means that all parts of the model receive the same level of texture detail.

For a beginner game asset like a barrel, exact texel density is less critical than getting a clean, distortion-free layout. If the barrel is a supporting prop that appears in the background, minor density inconsistencies are unlikely to be visible.

If you are building a **hero prop** - an object the player picks up, inspects closely, or sees at high resolution - texel density matters more. A reasonable starting point is to ensure that the barrel's side and caps all receive roughly proportional UV space relative to their actual surface area.

## Step 8 - Common mistakes and how to fix them

**All UV islands are on top of each other.**  
You pressed Unwrap without any seams, or the seams did not form closed loops. In Edit Mode, enable the Seam overlay and verify your seams are red and continuous.

**The side of the barrel is distorted.**  
The vertical seam might be missing, leaving the cylinder as a closed loop that cannot unfold. Select any vertical edge on the barrel's side and mark it as a seam, then unwrap again.

**The cap islands look like strange shapes instead of circles.**  
Check that you selected all vertices before unwrapping (press A). Also check that the cap faces are N-Gons or triangles - Blender handles both, but strangely shaped caps may indicate a non-manifold mesh.

**UV islands overlap.**  
Select all in the UV Editor (A) and use UV → Pack Islands to resolve overlaps automatically.

## Step 9 - Verify the UV layout is ready for texturing

Your UV layout is ready when:
- Three islands are visible: two caps and one side band
- No islands overlap (check with the checker texture)
- The side band is a clean rectangle with minimal distortion
- All islands fit within the 0–1 UV square
- The checker squares in the viewport look approximately even

Save the file before moving to the next tutorial.

## What comes next

With clean UV coordinates, you can now apply textures to the barrel that map correctly to every face. The natural next step in the workflow is exporting this asset to a game engine.

The [Blender to Unity FBX tutorial](/tutorials/game-engine-export/blender-to-unity-fbx/) covers how to export this barrel - with its UV coordinates intact - into Unity, set up materials, and verify that everything looks correct in the engine.

## External references

- [Blender Manual: UV Unwrapping](https://docs.blender.org/manual/en/latest/editors/uv/index.html)
- [Blender Manual: Mark Seam](https://docs.blender.org/manual/en/latest/modeling/meshes/editing/uv.html#seams)
- [Blender Manual: Pack Islands](https://docs.blender.org/manual/en/latest/editors/uv/editing/layout.html)
